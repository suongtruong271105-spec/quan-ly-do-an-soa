<?php

namespace App\Http\Controllers;

use App\Models\DangKi;
use Illuminate\Http\Request;
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
            'masv' => 'required',
            'madt' => 'required',
        ], [
            'masv.required' => 'Vui lòng truyền mã sinh viên.',
            'madt.required' => 'Vui lòng chọn đề tài muốn đăng ký.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $sinhVienId = $request->masv;
        $detaiId    = $request->madt;


        $reqSV = Request::create("/api/sinhvien/{$sinhVienId}", 'GET');
        $resSV = app()->handle($reqSV);

        if (!$resSV->isSuccessful()) {
            return response()->json([
                'success' => false,
                'message' => 'Sinh viên không tồn tại trong hệ thống.'
            ], 404);
        }


        $reqDT = Request::create("/api/de-tai/{$detaiId}", 'GET');
        $resDT = app()->handle($reqDT);

        if (!$resDT->isSuccessful()) {
            return response()->json([
                'success' => false,
                'message' => 'Đề tài không tồn tại trong hệ thống.'
            ], 404);
        }


        // 2. Kiểm tra sinh viên đã đăng ký chưa
        $daDangKy = DangKi::where('masv', $sinhVienId)->exists();
        if ($daDangKy) {
            return response()->json([
                'success' => false,
                'message' => 'Sinh viên này đã đăng ký một đề tài rồi, không thể đăng ký thêm.'
            ], 400);
        }

        // 3. Lưu dữ liệu
        try {
            DB::connection('dangky_conn')->beginTransaction();

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
