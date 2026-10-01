<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // 1. Khai báo API tổng hợp báo cáo
    public function getBaoCaoTongHop()
    {
        try {
            // Join 3 bảng và lấy các trường dữ liệu cần thiết
            $data = DB::table('dangky')
                ->join('sinhvien', 'dangky.sinhvien_id', '=', 'sinhvien.id')
                ->join('detai', 'dangky.detai_id', '=', 'detai.id')
                ->select(
                    'sinhvien.id as sinhvien_id',       // <-- BỔ SUNG: ID sinh viên (Thư)
                    'sinhvien.ma_sv',
                    'sinhvien.ho_ten as ten_sinh_vien',
                    'sinhvien.lop',
                    'detai.id as detai_id',             // <-- BỔ SUNG: ID đề tài (Thư)
                    'detai.ten_dt as ten_de_tai',
                    'detai.giang_vien_huong_dan',
                    'dangky.id as dang_ky_id',         // <-- BỔ SUNG: ID của lượt đăng ký để chấm điểm (Thư)
                    'dangky.created_at as ngay_dang_ky',
                    'dangky.diem'
                )
                ->orderBy('sinhvien.ma_sv', 'asc')
                ->get();

            // Lấy dữ liệu thống kê tổng quan cho Dashboard
            $thongKe = [
                'tong_sinh_vien' => DB::table('sinhvien')->count(),
                'tong_de_tai'    => DB::table('detai')->count(),
                'tong_dang_ky'   => DB::table('dangky')->count(),
                'da_cham_diem'   => DB::table('dangky')->whereNotNull('diem')->count(),
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
                'message' => 'Lỗi truy vấn: ' . $e->getMessage()
            ], 500);
        }
    }

    // 2. Trả về giao diện Web 
    public function index()
    {
        return view('dashboard');
    }
}
