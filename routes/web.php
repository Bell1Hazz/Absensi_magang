<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\UserController;

// Authentication Routes
Auth::routes();

// Home redirect
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard - PASTIKAN INI ADA
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Absensi routes
    Route::get('/absensi', [AbsensiController::class, 'form'])->name('absensi.form');
    Route::post('/absensi/masuk', [AbsensiController::class, 'masuk'])->name('absensi.masuk');
    Route::post('/absensi/pulang', [AbsensiController::class, 'pulang'])->name('absensi.pulang');
    Route::post('/absensi/update-shift', [AbsensiController::class, 'updateShift'])->name('absensi.update-shift');
    Route::get('/riwayat-absensi', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');
    
    // Izin routes
    Route::get('/izin', [IzinController::class, 'index'])->name('izin.index');
    Route::get('/izin/create', [IzinController::class, 'create'])->name('izin.create');
    Route::post('/izin', [IzinController::class, 'store'])->name('izin.store');
    Route::get('/daftar-izin', [IzinController::class, 'daftarIzin'])->name('izin.daftar');
    Route::post('/izin/{izin}/setujui', [IzinController::class, 'setujui'])->name('izin.setujui');
    Route::post('/izin/{izin}/tolak', [IzinController::class, 'tolak'])->name('izin.tolak');
    
    // User management
    Route::resource('users', UserController::class);
});

// Debug route untuk test
Route::get('/test-dashboard', function () {
    return 'Dashboard route is working! User: ' . (auth()->check() ? auth()->user()->name : 'Not authenticated');
})->middleware('auth');