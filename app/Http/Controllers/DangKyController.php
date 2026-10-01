<?php

namespace App\Http\Controllers;

use App\Models\DangKy;
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
        $validator = Validator::make($request->all(), [
            'detai_id'    => 'required|exists:detai,id',
            'sinhvien_id' => 'nullable|exists:sinhvien,id',
        ], [
            'detai_id.required' => 'Vui lòng chọn đề tài muốn đăng ký.',
            'detai_id.exists'   => 'Đề tài được chọn không tồn tại.',
            'sinhvien_id.exists' => 'Sinh viên không tồn tại trong hệ thống.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors'  => $validator->errors()
            ], 422);
        }

        $sinhVienId = Auth::id() ?? $request->sinhvien_id;
        $detaiId = $request->detai_id;

        if (!$sinhVienId) {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu thông tin sinh viên (vui lòng truyền sinhvien_id trong body hoặc thực hiện đăng nhập).'
            ], 400);
        }

        $daDangKy = DangKy::where('sinhvien_id', $sinhVienId)->exists();

        if ($daDangKy) {
            return response()->json([
                'success' => false,
                'message' => 'Sinh viên này đã đăng ký một đề tài rồi, không thể đăng ký thêm.'
            ], 400);
        }

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

    /**
     * API Nhập điểm
     */
    public function nhapDiem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dang_ky_id'  => 'required_without_all:sinhvien_id|exists:dangky,id',
            'sinhvien_id' => 'required_without:dang_ky_id|exists:sinhvien,id',
            'detai_id'    => 'required_with:sinhvien_id|exists:detai,id',
            'diem'        => 'required|numeric|min:0|max:10',
        ], [
            'dang_ky_id.exists'  => 'Mã đăng ký không tồn tại trong hệ thống.',
            'sinhvien_id.exists' => 'Sinh viên không tồn tại.',
            'detai_id.exists'    => 'Đề tài không tồn tại.',
            'diem.required'      => 'Vui lòng nhập điểm.',
            'diem.numeric'       => 'Điểm phải là chữ số.',
            'diem.min'           => 'Điểm không được nhỏ hơn 0.',
            'diem.max'           => 'Điểm không được lớn hơn 10.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            if ($request->filled('dang_ky_id')) {
                $dangKy = DangKy::find($request->dang_ky_id);
            } else {
                $dangKy = DangKy::where('sinhvien_id', $request->sinhvien_id)
                    ->where('detai_id', $request->detai_id)
                    ->first();
            }

            if (!$dangKy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin đăng ký đề tài!'
                ], 404);
            }

            $dangKy->diem = floatval($request->diem);
            $dangKy->save();

            return response()->json([
                'success' => true,
                'message' => 'Nhập điểm thành công!',
                'data'    => $dangKy
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra trong quá trình nhập điểm!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
