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
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Route yang butuh Login
Route::middleware(['auth'])->group(function () {
    Route::get('/users', [AuthController::class, 'index'])->name('users.index');
    Route::post('/users', [AuthController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [AuthController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AuthController::class, 'destroy'])->name('users.destroy');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


// Route Karyawan - Publik (Hanya Lihat)
Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');

// Route Karyawan - Wajib Login (Form & Aksi)
Route::middleware(['auth'])->group(function () {
    Route::get('karyawan/export-template', [KaryawanController::class, 'exportTemplate'])->name('karyawan.export-template');
    Route::post('karyawan/import', [KaryawanController::class, 'import'])->name('karyawan.import');
    Route::resource('karyawan', KaryawanController::class)->except(['index', 'show']);
});

// Route Karyawan - Show (Wildcard diletakkan paling bawah agar tidak bentrok dengan 'create' dll)
Route::get('/karyawan/{karyawan}', [KaryawanController::class, 'show'])->name('karyawan.show');
