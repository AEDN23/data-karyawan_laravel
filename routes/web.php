<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\KaryawanController;

Route::get('/', function () {
    return redirect()->route('karyawan.index');
});

Route::resource('karyawan', KaryawanController::class);
