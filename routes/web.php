<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/sinhvien', function () {
    return view('sinhvien');
});


Route::get('/dang-ky', function () {
    return view('dangky');
});

Route::get('/de-tai', function () {
    return view('de-tai');
});