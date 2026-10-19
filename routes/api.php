<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DangKyController;
use App\Http\Controllers\DeTaiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/sinhvien', [SinhVienController::class, 'index']);
Route::get('/sinhvien/{masv}', [SinhVienController::class, 'show']);
Route::post('/sinhvien', [SinhVienController::class, 'store']);
Route::put('/sinhvien/{masv}', [SinhVienController::class, 'update']);
Route::delete('/sinhvien/{masv}', [SinhVienController::class, 'destroy']);


Route::get('/dashboard/bao-cao', [DashboardController::class, 'getBaoCaoTongHop']);


Route::post('/dang-ky-de-tai', [DangKyController::class, 'store']);


Route::get('/de-tai', [DeTaiController::class, 'index']);
Route::get('/de-tai/{madt}', [DeTaiController::class, 'show']);
Route::post('/de-tai', [DeTaiController::class, 'store']);
Route::put('/de-tai/{madt}', [DeTaiController::class, 'update']);
Route::delete('/de-tai/{madt}', [DeTaiController::class, 'destroy']);
