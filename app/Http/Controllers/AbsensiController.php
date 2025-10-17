<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
// Tambahkan use statements di atas
use Yajra\DataTables\Facades\DataTables;


class AbsensiController extends Controller
{
    public function form()
    {
        $user = Auth::user();
        
        // Cek absensi hari ini
        $absensiHariIni = Absensi::where('user_id', $user->id)
                                ->whereDate('tanggal', today())
                                ->first();
        
        // Cek apakah ada absensi kemarin yang belum pulang
        $absensiKemarinBelumPulang = Absensi::where('user_id', $user->id)
                                           ->whereDate('tanggal', today()->subDay())
                                           ->whereNotNull('jam_masuk')
                                           ->whereNull('jam_pulang')
                                           ->first();
        
        return view('absensi.form', compact('absensiHariIni', 'absensiKemarinBelumPulang'));
    }

    public function masuk(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();
        $today = today();
        
        // Cek apakah sudah absen hari ini
        $existingAbsensi = Absensi::where('user_id', $user->id)
                                 ->whereDate('tanggal', $today)
                                 ->first();
        
        if ($existingAbsensi) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi hari ini!'
            ]);
        }
        
        $jamMasuk = Carbon::now()->format('H:i:s');
        $status = 'hadir';
        
        // Tentukan status berdasarkan jam masuk
        if (Carbon::now()->format('H:i') > '09:00') {
            $status = 'terlambat';
        }

        // Tentukan lokasi berdasarkan GPS
        $lokasi = Absensi::determineLocation($request->latitude, $request->longitude);
        
        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'jam_masuk' => $jamMasuk,
            'status' => $status,
            'latitude_masuk' => $request->latitude,
            'longitude_masuk' => $request->longitude,
            'lokasi_masuk' => $lokasi
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Absensi masuk berhasil dicatat!',
            'location' => $lokasi,
            'time' => $jamMasuk
        ]);
    }

    public function pulang(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'tanggal_pulang' => 'nullable|date' // Optional: untuk pulang hari sebelumnya
        ]);

        $user = Auth::user();
        $tanggalPulang = $request->tanggal_pulang ? Carbon::parse($request->tanggal_pulang) : today();
        
        $absensi = Absensi::where('user_id', $user->id)
                         ->whereDate('tanggal', $tanggalPulang)
                         ->first();
        
        if (!$absensi) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ditemukan data absensi masuk untuk tanggal tersebut!'
            ]);
        }
        
        if ($absensi->jam_pulang) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi pulang untuk tanggal tersebut!'
            ]);
        }

        // Tentukan lokasi berdasarkan GPS
        $lokasi = Absensi::determineLocation($request->latitude, $request->longitude);
        
        $jamPulang = Carbon::now()->format('H:i:s');
        
        $absensi->update([
            'jam_pulang' => $jamPulang,
            'latitude_pulang' => $request->latitude,
            'longitude_pulang' => $request->longitude,
            'lokasi_pulang' => $lokasi
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Absensi pulang berhasil dicatat!',
            'location' => $lokasi,
            'time' => $jamPulang,
            'date' => $tanggalPulang->format('d M Y')
        ]);
    }

    public function updateShift(Request $request)
    {
        $request->validate([
            'shift' => 'required|in:pagi,sore'
        ]);

        $user = Auth::user();
        $user->update(['shift' => $request->shift]);

        return response()->json([
            'success' => true,
            'message' => 'Shift berhasil diubah'
        ]);
    }


// Update method riwayat
public function riwayat(Request $request)
{
    // Jika request AJAX untuk DataTables
    if ($request->ajax()) {
        $absensi = Absensi::with('user')
                         ->select(['id', 'user_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status', 'lokasi_masuk', 'lokasi_pulang']);
        
        return DataTables::of($absensi)
            ->addIndexColumn()
            ->addColumn('nama', function($row) {
                return $row->user->name ?? 'N/A';
            })
            ->addColumn('shift', function($row) {
                return ucfirst($row->user->shift ?? 'pagi');
            })
            ->editColumn('tanggal', function($row) {
                return $row->tanggal->format('d M Y');
            })
            ->editColumn('jam_masuk', function($row) {
                return $row->jam_masuk ? '<span class="text-success font-weight-bold">'.$row->jam_masuk.'</span>' : '<span class="text-muted">-</span>';
            })
            ->editColumn('jam_pulang', function($row) {
                return $row->jam_pulang ? '<span class="text-success font-weight-bold">'.$row->jam_pulang.'</span>' : '<span class="text-muted">-</span>';
            })
            ->editColumn('lokasi_masuk', function($row) {
                $class = $row->lokasi_masuk == 'KANTOR' ? 'success' : 'warning';
                return '<span class="badge badge-'.$class.'">'.($row->lokasi_masuk ?? 'N/A').'</span>';
            })
            ->addColumn('status_badge', function($row) {
                if ($row->jam_pulang) {
                    return '<span class="badge badge-success">SUDAH PULANG</span>';
                } elseif ($row->jam_masuk) {
                    return '<span class="badge badge-danger">Absen Pulang</span>';
                } else {
                    return '<span class="badge badge-secondary">Belum Absen</span>';
                }
            })
            ->rawColumns(['jam_masuk', 'jam_pulang', 'lokasi_masuk', 'status_badge'])
            ->make(true);
    }
    
    return view('absensi.riwayat');
}

// Generate Fake Absensi Data
public function generateFakeData(Request $request)
{
    try {
        $faker = \Faker\Factory::create('id_ID');
        
        // Get all magang users
        $magangUsers = User::where('role', 'magang')->get();
        
        if ($magangUsers->count() == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada user magang. Generate user dulu!'
            ]);
        }
        
        $count = 0;
        
        // Generate absensi untuk 30 hari terakhir
        foreach ($magangUsers as $user) {
            for ($day = 29; $day >= 0; $day--) {
                $date = now()->subDays($day);
                
                // Skip weekend
                if ($date->isWeekend()) {
                    continue;
                }
                
                // Skip jika sudah ada absensi untuk tanggal ini
                if (Absensi::where('user_id', $user->id)->where('tanggal', $date->format('Y-m-d'))->exists()) {
                    continue;
                }
                
                // 85% chance user hadir
                if ($faker->boolean(85)) {
                    $jamMasuk = $faker->time('H:i:s', '09:00');
                    $status = \Carbon\Carbon::createFromFormat('H:i:s', $jamMasuk)->format('H:i') > '08:00' ? 'terlambat' : 'hadir';
                    $jamPulang = $faker->boolean(90) ? $faker->time('H:i:s', '18:00') : null;
                    
                    // GPS coordinates
                    $isInOffice = $faker->boolean(80);
                    $latMasuk = $isInOffice ? $faker->latitude(-7.846, -7.844) : $faker->latitude(-7.9, -7.7);
                    $lonMasuk = $isInOffice ? $faker->longitude(110.360, 110.363) : $faker->longitude(110.2, 110.5);
                    $lokasiMasuk = $isInOffice ? 'KANTOR' : 'DILUAR KANTOR';
                    
                    Absensi::create([
                        'user_id' => $user->id,
                        'tanggal' => $date->format('Y-m-d'),
                        'jam_masuk' => $jamMasuk,
                        'jam_pulang' => $jamPulang,
                        'status' => $status,
                        'latitude_masuk' => $latMasuk,
                        'longitude_masuk' => $lonMasuk,
                        'latitude_pulang' => $jamPulang ? $faker->latitude(-7.85, -7.84) : null,
                        'longitude_pulang' => $jamPulang ? $faker->longitude(110.35, 110.37) : null,
                        'lokasi_masuk' => $lokasiMasuk,
                        'lokasi_pulang' => $jamPulang ? ($faker->boolean(75) ? 'KANTOR' : 'DILUAR KANTOR') : null,
                        'created_at' => $date,
                    ]);
                    
                    $count++;
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Berhasil generate {$count} data absensi untuk {$magangUsers->count()} user magang!"
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal generate data absensi: ' . $e->getMessage()
        ]);
    }
}

// Export Absensi
public function export(Request $request)
{
    $startDate = $request->get('start_date', now()->startOfMonth());
    $endDate = $request->get('end_date', now()->endOfMonth());
    $userId = $request->get('user_id');
    $format = $request->get('format', 'xlsx');
    
    $filename = 'absensi_' . date('Y-m-d_H-i-s') . '.' . $format;
    
    return Excel::download(new AbsensiExport($startDate, $endDate, $userId), $filename);
}
}