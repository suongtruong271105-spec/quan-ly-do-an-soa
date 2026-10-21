<?php

namespace App\Http\Controllers;

use App\Models\DangKi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // 1. Lấy báo cáo tổng hợp thông qua API nội bộ (Chạy an toàn trên 1 cổng)
    public function getBaoCaoTongHop()
    {
        try {
            $dangkis = DangKi::all();

            // Lấy danh sách Sinh Viên từ API
            $reqSV = Request::create('/api/sinhvien', 'GET');
            $resSV = app()->handle($reqSV);
            if ($resSV->isSuccessful()) {
                $rawSV = json_decode($resSV->getContent(), true);
                $svArray = isset($rawSV['data']) ? $rawSV['data'] : $rawSV;
                $sinhviens = collect($svArray)->keyBy('masv');
            } else {
                $sinhviens = collect();
            }

            // Lấy danh sách Đề Tài từ API
            $reqDT = Request::create('/api/de-tai', 'GET');
            $resDT = app()->handle($reqDT);
            if ($resDT->isSuccessful()) {
                $rawDT = json_decode($resDT->getContent(), true);
                $dtArray = isset($rawDT['data']) ? $rawDT['data'] : $rawDT;
                $detais = collect($dtArray)->keyBy('madt');
            } else {
                $detais = collect();
            }

            // Map thông tin đăng ký với dữ liệu sinh viên và đề tài
            $data = $dangkis->map(function ($dk) use ($sinhviens, $detais) {
                $sv = $sinhviens->get($dk->masv);
                $dt = $detais->get($dk->madt);

                return [
                    'sinhvien_id'          => $dk->masv,
                    'ma_sv'                => $dk->masv,
                    'ten_sinh_vien'        => $sv ? ($sv['hoten'] ?? 'Không rõ') : 'Dữ liệu bị xóa',
                    'lop'                  => $sv ? ($sv['lop'] ?? '') : '',
                    'detai_id'             => $dk->madt,
                    'ten_de_tai'           => $dt ? ($dt['tendt'] ?? 'Không rõ') : 'Dữ liệu bị xóa',
                    'giang_vien_huong_dan' => $dt ? ($dt['giangvien'] ?? '') : '',
                    'dang_ky_id'           => $dk->id ?? 0,
                    'ngay_dang_ky'         => $dk->created_at ?? now(),
                ];
            })
            ->filter(function ($item) {

                return $item['ten_sinh_vien'] !== 'Dữ liệu bị xóa';
            })
            ->sortBy('ma_sv')->values();

            $thongKe = [
                'tong_sinh_vien' => $sinhviens->count(),
                'tong_de_tai'    => $detais->count(),
                'tong_dang_ky'   => $data->count(),
            ];

            return response()->json([
                'status'  => 'success',
                'message' => 'Lấy báo cáo thành công',
                'summary' => $thongKe,
                'data'    => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Lỗi xử lý dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    // 2. Trả về giao diện Dashboard
    public function index()
    {
        return view('dashboard');
    }
}