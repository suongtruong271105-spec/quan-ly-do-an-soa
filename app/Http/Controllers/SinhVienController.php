<?php

namespace App\Http\Controllers;

use App\Models\SinhVien;
use Illuminate\Http\Request;

class SinhVienController extends Controller
{
    // GET /api/sinhvien
    // Lấy danh sách tất cả sinh viên
    public function index()
    {
        $sinhViens = SinhVien::all();
        return response()->json($sinhViens);
    }

    // GET /api/sinhvien/{masv}
    // Lấy thông tin một sinh viên
    public function show($masv)
    {
        // Vì Model đã cấu hình $primaryKey = 'masv', hàm find() sẽ tự hiểu
        $sinhVien = SinhVien::find($masv);

        if (!$sinhVien) {
            return response()->json([
                'message' => 'Không tìm thấy sinh viên'
            ], 404);
        }

        return response()->json($sinhVien);
    }

    // POST /api/sinhvien
    // Thêm sinh viên
    public function store(Request $request)
    {
        $request->validate([
            // Bắt buộc trỏ rõ connection: sinhvien_conn
            'masv' => 'required|string|max:20|unique:sinhvien_conn.sinhvien,masv',
            'hoten' => 'required|string|max:100',
            'ngaysinh' => 'nullable|date',
            'lop' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100'
        ]);

        $sinhVien = SinhVien::create([
            'masv' => $request->masv,
            'hoten' => $request->hoten,
            'ngaysinh' => $request->ngaysinh,
            'lop' => $request->lop,
            'email' => $request->email
        ]);

        return response()->json([
            'message' => 'Thêm sinh viên thành công',
            'data' => $sinhVien
        ], 201);
    }

    // PUT /api/sinhvien/{masv}
    // Cập nhật sinh viên
    public function update(Request $request, $masv)
    {
        $sinhVien = SinhVien::find($masv);

        if (!$sinhVien) {
            return response()->json([
                'message' => 'Không tìm thấy sinh viên'
            ], 404);
        }

        $request->validate([
            // Check unique nhưng bỏ qua bản ghi hiện tại
            'masv' => 'required|string|max:20|unique:sinhvien_conn.sinhvien,masv,' . $masv . ',masv',
            'hoten' => 'required|string|max:100',
            'ngaysinh' => 'nullable|date',
            'lop' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100'
        ]);

        $sinhVien->update([
            'masv' => $request->masv,
            'hoten' => $request->hoten,
            'ngaysinh' => $request->ngaysinh,
            'lop' => $request->lop,
            'email' => $request->email
        ]);

        return response()->json([
            'message' => 'Cập nhật sinh viên thành công',
            'data' => $sinhVien
        ]);
    }

    // DELETE /api/sinhvien/{masv}
    // Xóa sinh viên
    public function destroy($masv)
    {
        $sinhVien = SinhVien::find($masv);

        if (!$sinhVien) {
            return response()->json([
                'message' => 'Không tìm thấy sinh viên'
            ], 404);
        }

        $sinhVien->delete();

        return response()->json([
            'message' => 'Xóa sinh viên thành công'
        ]);
    }
}