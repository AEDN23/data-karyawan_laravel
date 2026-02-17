<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\KaryawanController;

Route::get('/', function () {
    return redirect()->route('karyawan.index');
});

Route::get('karyawan/export-template', [KaryawanController::class, 'exportTemplate'])->name('karyawan.export-template');
Route::post('karyawan/import', [KaryawanController::class, 'import'])->name('karyawan.import');

Route::resource('karyawan', KaryawanController::class);
