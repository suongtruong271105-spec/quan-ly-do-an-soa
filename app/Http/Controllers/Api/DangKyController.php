<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DangKy;
use App\Models\DeTai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DangKyController extends Controller
{
    /**
     * Xử lý API sinh viên đăng ký đề tài
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'detai_id'    => 'required|exists:detai,id',
            'sinhvien_id' => 'nullable|exists:sinhvien,id',
        ], [
            'detai_id.required'   => 'Vui lòng chọn đề tài muốn đăng ký.',
            'detai_id.exists'     => 'Đề tài được chọn không tồn tại.',
            'sinhvien_id.exists'   => 'Sinh viên không tồn tại trong hệ thống.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Lấy ID sinh viên (Ưu tiên Auth::id(), nếu chưa đăng nhập thì lấy sinhvien_id từ Request)
        $sinhVienId = Auth::id() ?? $request->sinhvien_id;
        $detaiId = $request->detai_id;

        if (!$sinhVienId) {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu thông tin sinh viên (vui lòng truyền sinhvien_id trong body hoặc thực hiện đăng nhập).'
            ], 400);
        }

        // 2. Kiểm tra nghiệp vụ: Sinh viên đã đăng ký đề tài nào chưa
        $daDangKy = DangKy::where('sinhvien_id', $sinhVienId)->exists();

        if ($daDangKy) {
            return response()->json([
                'success' => false,
                'message' => 'Sinh viên này đã đăng ký một đề tài rồi, không thể đăng ký thêm.'
            ], 400);
        }

        // 3. Thực hiện lưu thông tin đăng ký
        try {
            DB::beginTransaction();

            $dangKy = DangKy::create([
                'sinhvien_id' => $sinhVienId,
                'detai_id'    => $detaiId,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký đề tài thành công!',
                'data'    => $dangKy
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra trong quá trình xử lý.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
