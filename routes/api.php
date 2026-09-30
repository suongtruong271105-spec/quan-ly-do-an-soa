<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// API trả về JSON dữ liệu tổng hợp
Route::get('/dashboard/bao-cao', [DashboardController::class, 'getBaoCaoTongHop']);