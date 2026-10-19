<?php

namespace App\Http\Controllers;

use App\Models\DangKi; // Chú ý tên Model DangKi (viết i ngắn theo file của bạn)
use App\Models\SinhVien;
use App\Models\DeTai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DangKyController extends Controller
{
    /**
     * Xử lý API sinh viên đăng ký đề tài chuẩn SOA
     */
    public function store(Request $request)
    {
        // Bước 1: Chỉ Validate định dạng đầu vào (Không dùng 'exists' nối bảng ở đây nữa)
        $validator = Validator::make($request->all(), [
            'madt' => 'required',
            'masv' => 'nullable',
        ], [
            'madt.required' => 'Vui lòng chọn đề tài muốn đăng ký.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Lấy mã sinh viên từ Request hoặc Auth
        $sinhVienId = Auth::check() ? Auth::user()->masv : $request->masv;
        $detaiId = $request->madt;

        if (!$sinhVienId) {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu thông tin sinh viên (vui lòng truyền masv trong body hoặc thực hiện đăng nhập).'
            ], 400);
        }

        // =====================================================================
        // BƯỚC 2: GIAO TIẾP SOA CHÉO SERVICE (THAY THẾ CHO FOREIGN KEY/EXISTS)
        // Service Đăng ký "hỏi" Service Sinh Viên xem có mã này không
        $sinhVienHopLe = SinhVien::where('masv', $sinhVienId)->exists();
        if (!$sinhVienHopLe) {
            return response()->json([
                'success' => false,
                'message' => 'Sinh viên không tồn tại trong hệ thống (Dữ liệu từ Student Service).'
            ], 404);
        }

        // Service Đăng ký "hỏi" Service Đề Tài xem có mã này không
        $deTaiHopLe = DeTai::where('madt', $detaiId)->exists();
        if (!$deTaiHopLe) {
            return response()->json([
                'success' => false,
                'message' => 'Đề tài được chọn không tồn tại (Dữ liệu từ Topic Service).'
            ], 404);
        }
        // =====================================================================

        // Bước 3: Kiểm tra logic nghiệp vụ tại Local Service (DangKi)
        $daDangKy = DangKi::where('masv', $sinhVienId)->exists();

        if ($daDangKy) {
            return response()->json([
                'success' => false,
                'message' => 'Sinh viên này đã đăng ký một đề tài rồi, không thể đăng ký thêm.'
            ], 400);
        }

        // Bước 4: Lưu dữ liệu
        try {
            DB::connection('dangky_conn')->beginTransaction(); // Bắt buộc dùng đúng connection

            $dangKy = DangKi::create([
                'masv'      => $sinhVienId,
                'madt'      => $detaiId,
                'trangthai' => 'Cho Duyet'
            ]);

            DB::connection('dangky_conn')->commit();

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký đề tài thành công!',
                'data'    => $dangKy
            ], 201);
        } catch (\Exception $e) {
            DB::connection('dangky_conn')->rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra trong quá trình xử lý.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
