<?php

namespace App\Http\Controllers;

use App\Models\DangKi;
use App\Models\SinhVien;
use App\Models\DeTai;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // 1. Khai báo API tổng hợp báo cáo (Chuẩn SOA)
    public function getBaoCaoTongHop()
    {
        try {
            // Bước 1: Lấy toàn bộ lượt đăng ký từ Service Đăng Ký
            $dangkis = DangKi::all();

            // Lấy danh sách các mã SV và mã Đề tài để tối ưu hóa truy vấn
            $listMaSV = $dangkis->pluck('masv')->unique()->toArray();
            $listMaDT = $dangkis->pluck('madt')->unique()->toArray();

            // Bước 2: Truy vấn độc lập sang Service Sinh Viên và Service Đề Tài
            $sinhviens = SinhVien::whereIn('masv', $listMaSV)->get()->keyBy('masv');
            $detais = DeTai::whereIn('madt', $listMaDT)->get()->keyBy('madt');

            // Bước 3: Lắp ráp (Map) dữ liệu bằng PHP để thay thế cho lệnh JOIN SQL
         $data = $dangkis->map(function ($dk) use ($sinhviens, $detais) {
        $sv = isset($sinhviens[$dk->masv]) ? $sinhviens[$dk->masv] : null;
        $dt = isset($detais[$dk->madt]) ? $detais[$dk->madt] : null;

        return [
            'sinhvien_id'          => $dk->masv,
            'ma_sv'                => $dk->masv,
            'ten_sinh_vien'        => $sv ? $sv->hoten : 'Dữ liệu bị xóa',
            'lop'                  => $sv ? $sv->lop : '',
            'detai_id'             => $dk->madt,
            'ten_de_tai'           => $dt ? $dt->tendt : 'Dữ liệu bị xóa',
            'giang_vien_huong_dan' => $dt ? $dt->giangvien : '',
            'dang_ky_id'           => $dk->id,
            'ngay_dang_ky'         => $dk->created_at,
            'diem'                 => $dk->diem ?? null 
        ];
    })
    ->filter(function ($item) {
        // CHỈ GIỮ LẠI những dòng có đầy đủ thông tin (loại bỏ các dòng có chữ "Dữ liệu bị xóa")
        return $item['ten_sinh_vien'] !== 'Dữ liệu bị xóa' && $item['ten_de_tai'] !== 'Dữ liệu bị xóa';
    })
    ->sortBy('ma_sv')->values();
            // Bước 4: Lấy dữ liệu thống kê tổng quan (truy vấn độc lập trên từng Model)
            $thongKe = [
                'tong_sinh_vien' => SinhVien::count(),
                'tong_de_tai'    => DeTai::count(),
                'tong_dang_ky'   => DangKi::count(),
                'da_cham_diem'   => DangKi::whereNotNull('diem')->count(),
            ];

            return response()->json([
                'status'  => 'success',
                'message' => 'Lấy báo cáo thành công (Kiến trúc SOA)',
                'summary' => $thongKe,
                'data'    => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    // 2. Trả về giao diện Web 
    public function index()
    {
        return view('dashboard');
    }
}