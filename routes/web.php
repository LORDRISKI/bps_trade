<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\Admin\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// PUBLIC
Route::prefix('trade')->name('trade.')->group(function () {
    Route::get('/', [TradeController::class, 'index'])->name('index');
    Route::get('/export', [TradeController::class, 'export'])->name('export');
});

// ADMIN
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
    Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');
    Route::delete('/upload/{id}', [UploadController::class, 'destroy'])->name('upload.destroy');
    Route::get('/upload/template', [UploadController::class, 'downloadTemplate'])->name('upload.template');
});

require __DIR__.'/auth.php';