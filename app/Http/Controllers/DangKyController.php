<?php

namespace App\Http\Controllers;

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

    /**
     * API Nhập điểm và tính xếp loại đồ án
     */
    public function nhapDiem(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'dang_ky_id' => 'required_without_all:sinhvien_id|exists:dangky,id',
            'sinhvien_id' => 'required_without:dang_ky_id|exists:sinhvien,id',
            'detai_id'   => 'required_with:sinhvien_id|exists:detai,id',
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
            // 2. Tìm bản ghi đăng ký (Hỗ trợ theo dang_ky_id hoặc cặp sinhvien_id + detai_id)
            if ($request->filled('dang_ky_id')) {
                $dangKy = \App\Models\DangKy::find($request->dang_ky_id);
            } else {
                $dangKy = \App\Models\DangKy::where('sinhvien_id', $request->sinhvien_id)
                    ->where('detai_id', $request->detai_id)
                    ->first();
            }

            if (!$dangKy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin đăng ký đề tài!'
                ], 404);
            }

            // 3. Cập nhật điểm
            $diem = floatval($request->diem);
            $dangKy->diem = $diem;
            $dangKy->save();

            // 4. Nghiệp vụ tính toán xếp loại và trạng thái (không lưu vào DB)
            $xepLoai = $this->tinhXepLoai($diem);
            $trangThai = $diem >= 4.0 ? 'Đạt' : 'Không đạt';

            return response()->json([
                'success' => true,
                'message' => 'Nhập điểm thành công!',
                'data'    => [
                    'id'          => $dangKy->id,
                    'sinhvien_id' => $dangKy->sinhvien_id,
                    'detai_id'    => $dangKy->detai_id,
                    'diem'        => $dangKy->diem,
                    'created_at'  => $dangKy->created_at,
                    'updated_at'  => $dangKy->updated_at,

                    // Kết quả tính toán nghiệp vụ kèm theo
                    'ket_qua' => [
                        'xep_loai'   => $xepLoai,
                        'trang_thai' => $trangThai,
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra trong quá trình nhập điểm!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Nghiệp vụ tính xếp loại theo thang điểm 10
     */
    private function tinhXepLoai(float $diem): string
    {
        // Có thể thay đổi đk điểm để xét xếp loại
        if ($diem >= 8.5) return 'Xuất sắc';
        if ($diem >= 7.5) return 'Giỏi';
        if ($diem >= 6.0) return 'Khá';
        if ($diem >= 5.5) return 'Trung bình';
        if ($diem >= 4.0) return 'Yếu';
        return 'Kém (Trượt)';
    }
}
