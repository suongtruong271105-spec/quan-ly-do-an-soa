<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DangKyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/sinhvien', [SinhVienController::class, 'index']);
Route::get('/sinhvien/{id}', [SinhVienController::class, 'show']);
Route::post('/sinhvien', [SinhVienController::class, 'store']);
Route::put('/sinhvien/{id}', [SinhVienController::class, 'update']);
Route::delete('/sinhvien/{id}', [SinhVienController::class, 'destroy']);


Route::get('/dashboard/bao-cao', [DashboardController::class, 'getBaoCaoTongHop']);


Route::post('/dang-ky-de-tai', [DangKyController::class, 'store']);
Route::put('/dang-ky-de-tai/nhap-diem', [DangKyController::class, 'nhapDiem']);
