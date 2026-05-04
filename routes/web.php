<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\UploadController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────
// PUBLIC — Halaman utama & data trade (akses bebas)
// ─────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

Route::prefix('trade')->name('trade.')->group(function () {
    Route::get('/', [TradeController::class, 'index'])->name('index');
    Route::get('/export', [TradeController::class, 'export'])->name('export');
    Route::get('/export/{id}', [TradeController::class, 'exportSingle'])->name('export.single');
    Route::get('/count', [TradeController::class, 'count'])->name('count'); // ← BARU
});

// ─────────────────────────────────────────────
// ADMIN AUTH — Login & Logout khusus admin
// ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
        Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
        Route::delete('/upload/{id}', [UploadController::class, 'destroy'])->name('upload.destroy');
        Route::get('/upload/template', [UploadController::class, 'downloadTemplate'])->name('upload.template');
    });
});

// ─────────────────────────────────────────────
// USER — Dashboard publik (tanpa login)
// ─────────────────────────────────────────────
Route::get('/dashboard', function () {
    return view('user.dashboard');
})->name('dashboard');

require __DIR__.'/auth.php';