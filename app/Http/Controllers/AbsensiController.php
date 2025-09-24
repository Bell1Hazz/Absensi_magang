<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        if (Carbon::now()->format('H:i') > '08:00') {
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

    public function riwayat()
    {
        $absensi = Absensi::with('user')
                         ->orderBy('tanggal', 'desc')
                         ->paginate(15);
        
        return view('absensi.riwayat', compact('absensi'));
    }
}