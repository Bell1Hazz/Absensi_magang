<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IzinController extends Controller
{
    public function index()
    {
        $izinList = Izin::where('user_id', Auth::id())
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

        return view('izin.index', compact('izinList'));
    }

    public function create()
    {
        return view('izin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_izin' => 'required|in:sakit,izin,cuti',
            'alasan' => 'required|string|max:500'
        ]);

        Izin::create([
            'user_id' => Auth::id(),
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jenis_izin' => $request->jenis_izin,
            'alasan' => $request->alasan,
            'status' => 'pending'
        ]);

        return redirect()->route('izin.index')->with('success', 'Pengajuan izin berhasil dikirim!');
    }

    public function daftarIzin()
    {
        $izinList = Izin::with('user')
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);

        return view('izin.daftar', compact('izinList'));
    }

    public function setujui(Request $request, Izin $izin)
    {
        $izin->update([
            'status' => 'disetujui',
            'keterangan_mentor' => $request->keterangan_mentor,
            'disetujui_oleh' => Auth::id(),
            'tanggal_disetujui' => now()
        ]);

        return redirect()->back()->with('success', 'Izin berhasil disetujui!');
    }

    public function tolak(Request $request, Izin $izin)
    {
        $request->validate([
            'keterangan_mentor' => 'required|string|max:500'
        ]);

        $izin->update([
            'status' => 'ditolak',
            'keterangan_mentor' => $request->keterangan_mentor,
            'disetujui_oleh' => Auth::id(),
            'tanggal_disetujui' => now()
        ]);

        return redirect()->back()->with('success', 'Izin berhasil ditolak!');
    }
}