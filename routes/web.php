<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('karyawan.index');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Karyawan (Dibuat publik kembali)

// CARA AKTIFKAN LOGIN: Lepas komentar (uncomment) baris middleware di bawah
Route::middleware(['auth'])->group(function () {
    Route::get('karyawan/export-template', [KaryawanController::class, 'exportTemplate'])->name('karyawan.export-template');
    Route::post('karyawan/import', [KaryawanController::class, 'import'])->name('karyawan.import');
    Route::resource('karyawan', KaryawanController::class);
});
