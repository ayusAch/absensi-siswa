<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\RekapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes - bisa diakses semua role
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==================== ADMIN ONLY ROUTES ====================
    Route::middleware('can:admin')->group(function () {
        // Kelas routes - hanya admin
        Route::resource('kelas', KelasController::class);

        // Siswa routes - hanya admin
        Route::resource('siswa', SiswaController::class);
        Route::get('/siswa/{id}/download-qr', [SiswaController::class, 'downloadQrCode'])->name('siswa.download.qr');
        Route::get('/siswa/{id}/regenerate-qr', [SiswaController::class, 'regenerateQrCode'])->name('siswa.regenerate.qr');

        // Guru routes - hanya admin
        Route::resource('guru', GuruController::class);
        Route::get('/guru/{guru}/create-user', [GuruController::class, 'createUser'])->name('guru.create-user');
        Route::post('/guru/{guru}/store-user', [GuruController::class, 'storeUser'])->name('guru.store-user');
        Route::get('/guru/{guru}/edit-user', [GuruController::class, 'editUser'])->name('guru.edit-user');
        Route::put('/guru/{guru}/update-user', [GuruController::class, 'updateUser'])->name('guru.update-user');
        Route::delete('/guru/{guru}/destroy-user', [GuruController::class, 'destroyUser'])->name('guru.destroy-user');
        Route::get('/guru-users', [GuruController::class, 'listUsers'])->name('guru.user-list');
    });

    // ==================== GURU & ADMIN ROUTES ====================
    // Sementara tanpa middleware guru, kita handle di controller
    Route::prefix('absensi')->group(function () {
        Route::get('/scanner', [AbsensiController::class, 'scanner'])->name('absensi.scanner');
        Route::post('/scan-qr', [AbsensiController::class, 'scanQrCode'])->name('absensi.scan-qr');
        Route::get('/rekap-harian', [AbsensiController::class, 'rekapHarian'])->name('absensi.rekap-harian');
        Route::get('/siswa-by-kelas/{kelasId}', [AbsensiController::class, 'getSiswaByKelas'])->name('absensi.siswa-by-kelas');

        // Resource routes untuk absensi
        Route::get('/', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/create', [AbsensiController::class, 'create'])->name('absensi.create');
        Route::post('/', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/{id}', [AbsensiController::class, 'show'])->name('absensi.show');
        Route::delete('/{id}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    });

    // Route Rekap untuk Guru & Admin
    Route::prefix('rekap')->group(function () {
        Route::get('/', [RekapController::class, 'index'])->name('rekap.index');
        Route::get('/export', [RekapController::class, 'exportPdf'])->name('rekap.export');
        Route::get('/kelas/{kelasId}/detail', [RekapController::class, 'detailSiswa'])->name('rekap.detail-siswa');
    });
});

require __DIR__ . '/auth.php';