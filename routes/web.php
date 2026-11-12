<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Admin Controllers
use App\Http\Controllers\Admin\AnggotaController as AdminAnggotaController;
use App\Http\Controllers\Admin\SimpananController as AdminSimpananController;
use App\Http\Controllers\Admin\PengajuanPinjamanController as AdminPengajuanController;
use App\Http\Controllers\Admin\PinjamanController as AdminPinjamanController;
use App\Http\Controllers\Admin\AngsuranController as AdminAngsuranController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\QrisController as AdminQrisController;

// Anggota Controllers
use App\Http\Controllers\Anggota\SimpananController as AnggotaSimpananController;
use App\Http\Controllers\Anggota\PengajuanPinjamanController as AnggotaPengajuanController;
use App\Http\Controllers\Anggota\AngsuranController as AnggotaAngsuranController;
use App\Http\Controllers\Anggota\BayarController as AnggotaBayarController;

// Pimpinan Controllers
use App\Http\Controllers\Pimpinan\LaporanController as PimpinanLaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Redirect Fallback untuk /home jika user menekan tombol 'Back' browser setelah login
Route::get('/home', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'pimpinan') return redirect()->route('pimpinan.dashboard');
        if ($role === 'anggota') return redirect()->route('anggota.dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// ROLE: ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Anggota
    Route::get('/anggota', [AdminAnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/anggota/create', [AdminAnggotaController::class, 'create'])->name('anggota.create');
    Route::post('/anggota', [AdminAnggotaController::class, 'store'])->name('anggota.store');
    Route::get('/anggota/{id}', [AdminAnggotaController::class, 'show'])->name('anggota.show');
    Route::get('/anggota/{id}/edit', [AdminAnggotaController::class, 'edit'])->name('anggota.edit');
    Route::put('/anggota/{id}', [AdminAnggotaController::class, 'update'])->name('anggota.update');
    Route::patch('/anggota/{id}/toggle', [AdminAnggotaController::class, 'toggleStatus'])->name('anggota.toggle');

    // Simpanan
    Route::get('/simpanan', [AdminSimpananController::class, 'index'])->name('simpanan.index');
    Route::get('/simpanan/create', [AdminSimpananController::class, 'create'])->name('simpanan.create');
    Route::post('/simpanan', [AdminSimpananController::class, 'store'])->name('simpanan.store');
    Route::get('/simpanan/{id}', [AdminSimpananController::class, 'show'])->name('simpanan.show');

    // Pengajuan Pinjaman
    Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/{id}', [AdminPengajuanController::class, 'show'])->name('pengajuan.show');
    Route::post('/pengajuan/{id}/setujui', [AdminPengajuanController::class, 'setujui'])->name('pengajuan.setujui');
    Route::post('/pengajuan/{id}/tolak', [AdminPengajuanController::class, 'tolak'])->name('pengajuan.tolak');

    // Pinjaman & Angsuran
    Route::get('/pinjaman', [AdminPinjamanController::class, 'index'])->name('pinjaman.index');
    Route::get('/pinjaman/{id}', [AdminPinjamanController::class, 'show'])->name('pinjaman.show');
    Route::post('/angsuran', [AdminAngsuranController::class, 'store'])->name('angsuran.store');
    Route::get('/angsuran/{id}', [AdminAngsuranController::class, 'show'])->name('angsuran.show');

    // Laporan
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/simpanan', [AdminLaporanController::class, 'simpanan'])->name('laporan.simpanan');
    Route::get('/laporan/pinjaman', [AdminLaporanController::class, 'pinjaman'])->name('laporan.pinjaman');
    Route::get('/laporan/angsuran', [AdminLaporanController::class, 'angsuran'])->name('laporan.angsuran');
    Route::get('/laporan/anggota', [AdminLaporanController::class, 'anggota'])->name('laporan.anggota');
    Route::get('/laporan/cetak-simpanan', [AdminLaporanController::class, 'cetakSimpanan'])->name('laporan.cetak-simpanan');
    Route::get('/laporan/cetak-angsuran', [AdminLaporanController::class, 'cetakAngsuran'])->name('laporan.cetak-angsuran');

    // Pengaturan & QRIS
    Route::get('/pengaturan', [AdminSettingController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [AdminSettingController::class, 'update'])->name('pengaturan.update');
    Route::get('/qris/preview', [AdminQrisController::class, 'preview'])->name('qris.preview');
});

// ==========================================
// ROLE: ANGGOTA
// ==========================================
Route::middleware(['auth', 'role:anggota'])->prefix('anggota')->name('anggota.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'anggota'])->name('dashboard');

    // Simpanan (Buku Tabungan Saya)
    Route::get('/simpanan', [AnggotaSimpananController::class, 'index'])->name('simpanan.index');

    // Pengajuan Pinjaman Saya
    Route::get('/pengajuan', [AnggotaPengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/buat', [AnggotaPengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [AnggotaPengajuanController::class, 'store'])->name('pengajuan.store');

    // Angsuran Saya
    Route::get('/angsuran', [AnggotaAngsuranController::class, 'index'])->name('angsuran.index');

    // Bayar via QRIS
    Route::get('/bayar/angsuran/{pinjaman_id}', [AnggotaBayarController::class, 'angsuran'])->name('bayar.angsuran');
    Route::get('/bayar/simpanan/{jenis_id}', [AnggotaBayarController::class, 'simpanan'])->name('bayar.simpanan');
});

// ==========================================
// ROLE: PIMPINAN
// ==========================================
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'pimpinan'])->name('dashboard');

    // Laporan Monitoring
    Route::get('/laporan', [PimpinanLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/simpanan', [PimpinanLaporanController::class, 'simpanan'])->name('laporan.simpanan');
    Route::get('/laporan/pinjaman', [PimpinanLaporanController::class, 'pinjaman'])->name('laporan.pinjaman');
    Route::get('/laporan/angsuran', [PimpinanLaporanController::class, 'angsuran'])->name('laporan.angsuran');
    Route::get('/laporan/anggota', [PimpinanLaporanController::class, 'anggota'])->name('laporan.anggota');
});
