<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DashboardController;

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
// Tambahkan di group middleware auth
Route::middleware(['auth'])->group(function () {
    // ... existing routes ...
    
    // Profil routes untuk magang only
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::post('/profil/upload-photo', [ProfilController::class, 'uploadPhoto'])->name('profil.upload-photo');
    Route::delete('/profil/delete-photo', [ProfilController::class, 'deletePhoto'])->name('profil.delete-photo');
});
// Debug route untuk cek foto profil
Route::get('/debug-photo-user', function() {
    $user = auth()->user();
    
    if (!$user) {
        return 'Not authenticated';
    }
    
    return [
        'user_id' => $user->id,
        'user_name' => $user->name,
        'profile_photo_field' => $user->profile_photo,
        'has_profile_photo' => $user->hasProfilePhoto(),
        'profile_photo_url' => $user->profile_photo_url,
        'storage_exists' => $user->profile_photo ? Storage::disk('public')->exists($user->profile_photo) : false,
        'public_file_exists' => $user->profile_photo ? file_exists(public_path('storage/' . $user->profile_photo)) : false,
        'storage_link_exists' => is_link(public_path('storage')),
        'storage_link_target' => is_link(public_path('storage')) ? readlink(public_path('storage')) : 'No link'
    ];
})->middleware('auth');