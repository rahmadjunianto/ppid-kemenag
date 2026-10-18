<?php

use App\Http\Controllers\PpidController;
use Illuminate\Support\Facades\Route;

// Redirect halaman utama langsung ke Informasi Berkala (opsional)
Route::redirect('/', '/page/informasi-berkala');

// Halaman daftar Informasi Publik (mirip URL Kemenag Jatim: /page/informasi-berkala)
Route::get('/page/{slug}', [PpidController::class, 'index'])
    ->whereIn('slug', [
        'informasi-berkala',
        'informasi-serta-merta',
        'informasi-setiap-saat',
        'informasi-dikecualikan',
    ])
    ->name('ppid.index');

// Route untuk melihat/mengunduh PDF & mencatat statistik unduhan
Route::get('/ppid/dokumen/{id}/download', [PpidController::class, 'downloadItem'])
    ->name('ppid.item.download');

// Route untuk membaca halaman artikel/teks langsung
Route::get('/ppid/konten/{id}', [PpidController::class, 'showItem'])
    ->name('ppid.item.show');
    // Halaman Tabel LHKPN Pejabat per Tahun
Route::get('/page/lhkpn', [PpidController::class, 'lhkpn'])->name('ppid.lhkpn');
Route::get('/ppid/lhkpn/{id}/download', [PpidController::class, 'downloadLhkpn'])->name('ppid.lhkpn.download');
Route::get('/page/dipa', [PpidController::class, 'dipa'])->name('ppid.dipa');
Route::get('/ppid/dipa/{id}/download', [PpidController::class, 'downloadDipa'])->name('ppid.dipa.download');
Route::get('/page/arsip/{type}', [PpidController::class, 'yearlyDocuments'])->name('ppid.yearly');
Route::get('/ppid/arsip/{id}/download', [PpidController::class, 'downloadYearly'])->name('ppid.yearly.download');