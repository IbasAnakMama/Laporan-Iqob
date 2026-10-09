<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PersyaratanController;
use App\Http\Controllers\Musrif\IqobController as MusrifIqob;
use App\Http\Controllers\Musrif\SantriController;
use App\Http\Controllers\Musrif\LaporanController;
use App\Http\Controllers\Santri\IqobController as SantriIqob;
use App\Http\Controllers\Admin\MusrifController;
use App\Http\Controllers\Admin\MotivasiController;
use App\Http\Controllers\Admin\MabnaController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'form'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// File dilayani lewat controller agar santri tidak bisa buka file milik orang lain
Route::middleware('auth')->get('/file/{tipe}/{id}', [FileController::class, 'show'])
    ->whereIn('tipe', ['surat', 'pengumpulan', 'persyaratan'])->name('file');

// ===== PROFIL & FOTO (semua peran) =====
Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::post('/profil/foto', [ProfilController::class, 'update'])->name('profil.update');
    Route::delete('/profil/foto', [ProfilController::class, 'hapus'])->name('profil.hapus');
    Route::get('/foto/{user}', [ProfilController::class, 'foto'])->whereNumber('user')->name('foto');
});

// ===== AREA SANTRI =====
Route::middleware(['auth', 'role:santri'])->prefix('santri')->name('santri.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'santri'])->name('dashboard');
    Route::get('/laporan', [SantriIqob::class, 'laporan'])->name('laporan');
    Route::get('/upload', [SantriIqob::class, 'upload'])->name('upload');
    Route::post('/upload/{iqob}', [SantriIqob::class, 'store'])->name('upload.store');
    Route::get('/persyaratan', [PersyaratanController::class, 'index'])->name('persyaratan');
});

// ===== AREA SUPER ADMIN =====
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::get('/mabna', [MabnaController::class, 'index'])->name('mabna.index');
    Route::post('/mabna', [MabnaController::class, 'store'])->name('mabna.store');
    Route::put('/mabna/{mabna}', [MabnaController::class, 'update'])->name('mabna.update');
    Route::delete('/mabna/{mabna}', [MabnaController::class, 'destroy'])->name('mabna.destroy');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    Route::get('/musrif', [MusrifController::class, 'index'])->name('musrif.index');
    Route::post('/musrif', [MusrifController::class, 'store'])->name('musrif.store');
    Route::post('/musrif/{user}/kamar', [MusrifController::class, 'kamar'])->name('musrif.kamar');
    Route::delete('/musrif/{user}', [MusrifController::class, 'destroy'])->name('musrif.destroy');
    Route::get('/santri', [SantriController::class, 'index'])->name('santri.index');
    Route::post('/santri', [SantriController::class, 'store'])->name('santri.store');
    Route::delete('/santri/{user}', [SantriController::class, 'destroy'])->name('santri.destroy');
    Route::get('/motivasi', [MotivasiController::class, 'index'])->name('motivasi.index');
    Route::post('/motivasi', [MotivasiController::class, 'store'])->name('motivasi.store');
    Route::delete('/motivasi/{motivasi}', [MotivasiController::class, 'destroy'])->name('motivasi.destroy');
});

// ===== AREA MUSRIF =====
Route::middleware(['auth', 'role:musrif'])->prefix('musrif')->name('musrif.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'musrif'])->name('dashboard');

    Route::get('/iqob', [MusrifIqob::class, 'index'])->name('iqob.index');
    Route::get('/iqob/beri', [MusrifIqob::class, 'create'])->name('iqob.create');
    Route::post('/iqob', [MusrifIqob::class, 'store'])->name('iqob.store');
    Route::get('/iqob/{iqob}', [MusrifIqob::class, 'edit'])->name('iqob.edit');
    Route::put('/iqob/{iqob}', [MusrifIqob::class, 'update'])->name('iqob.update');
    Route::delete('/iqob/{iqob}', [MusrifIqob::class, 'destroy'])->name('iqob.destroy');
    Route::get('/kamar/{kamar}/santri', [MusrifIqob::class, 'santriByKamar'])->whereNumber('kamar')->name('kamar.santri');

    Route::get('/santri', [SantriController::class, 'index'])->name('santri.index');
    Route::post('/santri', [SantriController::class, 'store'])->name('santri.store');
    Route::delete('/santri/{user}', [SantriController::class, 'destroy'])->name('santri.destroy');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    Route::get('/persyaratan', [PersyaratanController::class, 'index'])->name('persyaratan');
    Route::post('/persyaratan', [PersyaratanController::class, 'store'])->name('persyaratan.store');
    Route::delete('/persyaratan/{persyaratan}', [PersyaratanController::class, 'destroy'])->name('persyaratan.destroy');
});
