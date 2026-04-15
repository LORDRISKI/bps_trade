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
});


// ─────────────────────────────────────────────
// ADMIN AUTH — Login & Logout khusus admin
// ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Halaman login admin (guest only — kalau sudah login admin, redirect ke dashboard)
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // ─── Area Admin (wajib login & role=admin) ───
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
        Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
        Route::delete('/upload/{id}', [UploadController::class, 'destroy'])->name('upload.destroy');
        Route::get('/upload/template', [UploadController::class, 'downloadTemplate'])->name('upload.template');
    });

});


// ─────────────────────────────────────────────
// USER AUTH — Dashboard & Profile (user biasa)
// Hanya untuk user yang sudah login (bukan admin)
// ─────────────────────────────────────────────
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
