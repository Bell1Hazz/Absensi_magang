<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Debug untuk melihat masalah
        if (!$user) {
            return redirect()->route('login');
        }
        
        if ($user->isMentor()) {
            $totalMagang = User::where('role', 'magang')->count();
            $hadirHariIni = Absensi::whereDate('tanggal', today())
                                 ->whereNotNull('jam_masuk')
                                 ->count();
            $absenHariIni = $totalMagang - $hadirHariIni;
            
            // Debug data untuk mentor
            logger()->info('Mentor Dashboard Data:', [
                'totalMagang' => $totalMagang,
                'hadirHariIni' => $hadirHariIni,
                'absenHariIni' => $absenHariIni
            ]);
            
            return view('dashboard', compact('totalMagang', 'hadirHariIni', 'absenHariIni'));
        } else {
            $absensiHariIni = Absensi::where('user_id', $user->id)
                                   ->whereDate('tanggal', today())
                                   ->first();
            
            return view('dashboard', compact('absensiHariIni'));
        }
    }
}