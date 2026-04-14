<?php

use Illuminate\Support\Facades\Route;

// Siswa
use App\Http\Controllers\Siswa\AuthController;
use App\Http\Controllers\Siswa\AkunController;
use App\Http\Controllers\Siswa\DashboardController;
use App\Http\Controllers\Siswa\LaporanPengaduanController;

// Pegawai
use App\Http\Controllers\Pegawai\AuthController as PegawaiAuthController;
use App\Http\Controllers\Pegawai\AkunController as PegawaiAkunController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\Pegawai\LaporanController as PegawaiLaporanController;

// Guru
use App\Http\Controllers\Guru\AuthController as GuruAuthController;
use App\Http\Controllers\Guru\AkunController as GuruAkunController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\LaporanController as GuruLaporanController;

// Admin
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AkunController as AdminAkunController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\LaporanAspirasiController as AdminLaporanController;

use App\Http\Controllers\SuperAdmin\AuthController as SuperAuthController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperDashboardController;
use App\Http\Controllers\SuperAdmin\AkunController as SuperAkunController;
use App\Http\Controllers\SuperAdmin\KategoriController as SuperKategoriController;
use App\Http\Controllers\SuperAdmin\TanggapanController as SuperTanggapanController;
use App\Http\Controllers\SuperAdmin\SiswaController as SuperSiswaController;
use App\Http\Controllers\SuperAdmin\PegawaiController as SuperPegawaiController;
use App\Http\Controllers\SuperAdmin\GuruController as SuperGuruController;
use App\Http\Controllers\SuperAdmin\SiswaImportController;
use App\Http\Controllers\SuperAdmin\PegawaiImportController;
use App\Http\Controllers\SuperAdmin\GuruImportController;
use App\Http\Controllers\SuperAdmin\AdminController as SuperAdminAdminController;

use App\Http\Controllers\UserTanggapanController;

// ─────────────────────────────────────────────
// Welcome
// ─────────────────────────────────────────────
Route::get('/', function () {
    $tanggapan = \App\Models\TanggapanAplikasi::with('user')
        ->where('is_tampil', true)
        ->latest()
        ->get();
    return view('welcome', compact('tanggapan'));
})->name('welcome');

// ─────────────────────────────────────────────
// Login Gabungan (Siswa, Guru, Pegawai)
// ─────────────────────────────────────────────
Route::get('/login', function () {
    return view('siswa.auth.login');
})->name('login');

// ─────────────────────────────────────────────
// Siswa
// ─────────────────────────────────────────────
Route::prefix('siswa')->name('siswa.')->group(function () {

    Route::middleware('guest:siswa')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware('auth:siswa')->group(function () {
        Route::get('/dasboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::singleton('/akun', AkunController::class)->except('show');
        Route::put('/akun/password', [AkunController::class, 'updatePassword'])->name('akun.password');
        Route::post('laporan/{aspirasi}/feedback', [LaporanPengaduanController::class, 'feedback'])->name('laporan.feedback');
        Route::post('laporan/{laporan}/komentar', [LaporanPengaduanController::class, 'storeKomentar'])->name('laporan.komentar');
        Route::resource('laporan', LaporanPengaduanController::class)->except(['edit', 'update']);
        Route::get('tanggapan', [UserTanggapanController::class, 'index'])->name('tanggapan.index');
        Route::post('tanggapan', [UserTanggapanController::class, 'store'])->name('tanggapan.store');
    });
});

// ─────────────────────────────────────────────
// Pegawai
// ─────────────────────────────────────────────
Route::prefix('pegawai')->name('pegawai.')->group(function () {

    Route::middleware('guest:pegawai')->group(function () {
        Route::post('/login', [PegawaiAuthController::class, 'login'])->name('login');
    });

    Route::middleware('auth:pegawai')->group(function () {
        Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [PegawaiAuthController::class, 'logout'])->name('logout');

        // ── Akun ──
        Route::get('/akun', [PegawaiAkunController::class, 'index'])->name('akun');
        Route::put('/akun', [PegawaiAkunController::class, 'updateProfile'])->name('akun.update');
        Route::put('/akun/password', [PegawaiAkunController::class, 'updatePassword'])->name('akun.password');

        // ── Laporan ──
        Route::post('laporan/{aspirasi}/feedback', [PegawaiLaporanController::class, 'feedback'])->name('laporan.feedback');
        Route::post('laporan/{laporan}/komentar', [PegawaiLaporanController::class, 'storeKomentar'])->name('laporan.komentar');
        Route::resource('laporan', PegawaiLaporanController::class)->except(['edit', 'update']);
        Route::get('tanggapan', [UserTanggapanController::class, 'index'])->name('tanggapan.index');
        Route::post('tanggapan', [UserTanggapanController::class, 'store'])->name('tanggapan.store');
    });
});

// ─────────────────────────────────────────────
// Guru
// ─────────────────────────────────────────────
Route::prefix('guru')->name('guru.')->group(function () {

    Route::middleware('guest:guru')->group(function () {
        Route::post('/login', [GuruAuthController::class, 'login'])->name('login');
    });

    Route::middleware('auth:guru')->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [GuruAuthController::class, 'logout'])->name('logout');

        // ── Akun ──
        Route::get('/akun', [GuruAkunController::class, 'index'])->name('akun');
        Route::put('/akun', [GuruAkunController::class, 'updateProfile'])->name('akun.update');
        Route::put('/akun/password', [GuruAkunController::class, 'updatePassword'])->name('akun.password');

        // ── Laporan ──
        Route::post('laporan/{aspirasi}/feedback', [GuruLaporanController::class, 'feedback'])->name('laporan.feedback');
        Route::post('laporan/{laporan}/komentar', [GuruLaporanController::class, 'storeKomentar'])->name('laporan.komentar');
        Route::resource('laporan', GuruLaporanController::class)->except(['edit', 'update']);
        Route::get('tanggapan', [UserTanggapanController::class, 'index'])->name('tanggapan.index');
        Route::post('tanggapan', [UserTanggapanController::class, 'store'])->name('tanggapan.store');
    });
});

// ─────────────────────────────────────────────
// Admin & Super Admin Area
// ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        // ── Area Admin Biasa ──
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/akun', [AdminAkunController::class, 'index'])->name('akun');
        Route::post('/akun', [AdminAkunController::class, 'updateProfile']);
        Route::post('/akun/password', [AdminAkunController::class, 'updatePassword'])->name('akun.password');
        
        Route::get('laporan/cetak', [AdminLaporanController::class, 'cetakPdf'])->name('laporan.cetak');
        Route::post('laporan/{laporan}/komentar', [AdminLaporanController::class, 'storeKomentar'])->name('laporan.komentar');
        Route::resource('laporan', AdminLaporanController::class)->only(['index', 'show', 'update']);
    });
});

// ── Area Khusus Super Admin ──
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    // Auth Super Admin
    Route::middleware('guest:superadmin')->group(function () {
        Route::get('/login', [SuperAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [SuperAuthController::class, 'login']);
    });

    Route::middleware(['auth:superadmin'])->group(function () {
        Route::post('/logout', [SuperAuthController::class, 'logout'])->name('logout');
        
        Route::get('/dashboard', [SuperDashboardController::class, 'index'])->name('dashboard');
    Route::get('/akun', [SuperAkunController::class, 'index'])->name('akun');
    Route::post('/akun', [SuperAkunController::class, 'updateProfile']);
    Route::post('/akun/password', [SuperAkunController::class, 'updatePassword'])->name('akun.password');

    // Admin Management
    Route::resource('admin', SuperAdminAdminController::class);

    Route::resource('kategori', SuperKategoriController::class);
    Route::resource('tanggapan-pengguna', SuperTanggapanController::class)->only(['index', 'destroy', 'toggleStatus']);
    Route::post('tanggapan-pengguna/{tanggapan}/toggle-status', [SuperTanggapanController::class, 'toggleStatus'])->name('tanggapan-pengguna.toggle-status');

    // ── Pengguna (Siswa, Pegawai, Guru) ──
    Route::prefix('pengguna')->name('pengguna.')->group(function () {
        // Siswa
        Route::get('siswa/export/excel', [SuperSiswaController::class, 'exportExcel'])->name('siswa.export.excel');
        Route::get('siswa/export/pdf', [SuperSiswaController::class, 'exportPdf'])->name('siswa.export.pdf');
        Route::get('siswa/template', [SiswaImportController::class, 'downloadTemplate'])->name('siswa.template');
        Route::post('siswa/import',  [SiswaImportController::class, 'import'])->name('siswa.import');
        Route::resource('siswa', SuperSiswaController::class);

        // Pegawai
        Route::get('pegawai/export/excel', [SuperPegawaiController::class, 'exportExcel'])->name('pegawai.export.excel');
        Route::get('pegawai/export/pdf', [SuperPegawaiController::class, 'exportPdf'])->name('pegawai.export.pdf');
        Route::get('pegawai/template', [PegawaiImportController::class, 'downloadTemplate'])->name('pegawai.template');
        Route::post('pegawai/import',  [PegawaiImportController::class, 'import'])->name('pegawai.import');
        Route::resource('pegawai', SuperPegawaiController::class);

        // Guru
        Route::get('guru/export/excel', [SuperGuruController::class, 'exportExcel'])->name('guru.export.excel');
        Route::get('guru/export/pdf', [SuperGuruController::class, 'exportPdf'])->name('guru.export.pdf');
        Route::get('guru/template', [GuruImportController::class, 'downloadTemplate'])->name('guru.template');
        Route::post('guru/import',  [GuruImportController::class, 'import'])->name('guru.import');
        Route::resource('guru', SuperGuruController::class);
        });
    });
});
