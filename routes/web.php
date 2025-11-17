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
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kelas routes
    Route::resource('kelas', KelasController::class);

    // Siswa routes
    Route::resource('siswa', SiswaController::class);
    Route::get('/siswa/{id}/download-qr', [SiswaController::class, 'downloadQrCode'])->name('siswa.download.qr');
    Route::get('/siswa/{id}/regenerate-qr', [SiswaController::class, 'regenerateQrCode'])->name('siswa.regenerate.qr');

    // Guru routes
    Route::resource('guru', GuruController::class);

    // Rekap routes
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
    Route::get('/rekap/export', [RekapController::class, 'exportPdf'])->name('rekap.export');
    Route::get('/rekap/kelas/{kelasId}/detail', [RekapController::class, 'detailSiswa'])->name('rekap.detail-siswa');

    // Absensi routes
    Route::get('/absensi/scanner', [AbsensiController::class, 'scanner'])->name('absensi.scanner'); 
    Route::resource('absensi', AbsensiController::class)->except(['edit', 'update', 'destroy']); 
    Route::post('/absensi/scan-qr', [AbsensiController::class, 'scanQrCode'])->name('absensi.scan-qr');
    Route::get('/absensi/rekap-harian', [AbsensiController::class, 'rekapHarian'])->name('absensi.rekap-harian');
    Route::get('/absensi/siswa-by-kelas/{kelasId}', [AbsensiController::class, 'getSiswaByKelas'])->name('absensi.siswa-by-kelas');
    Route::delete('/absensi/{absensi}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
});

require __DIR__ . '/auth.php';