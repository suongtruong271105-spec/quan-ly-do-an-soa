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


    // GET /api/sinhvien/{id}
    // Lấy thông tin một sinh viên
    public function show($id)
    {
        $sinhVien = SinhVien::find($id);

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
            'ma_sv' => 'required|string|max:20|unique:sinhvien,ma_sv',
            'ho_ten' => 'required|string|max:100',
            'lop' => 'required|string|max:50'
        ]);

        $sinhVien = SinhVien::create([
            'ma_sv' => $request->ma_sv,
            'ho_ten' => $request->ho_ten,
            'lop' => $request->lop
        ]);

        return response()->json([
            'message' => 'Thêm sinh viên thành công',
            'data' => $sinhVien
        ], 201);
    }


    // PUT /api/sinhvien/{id}
    // Cập nhật sinh viên
    public function update(Request $request, $id)
    {
        $sinhVien = SinhVien::find($id);

        if (!$sinhVien) {
            return response()->json([
                'message' => 'Không tìm thấy sinh viên'
            ], 404);
        }

        $request->validate([
            'ma_sv' => 'required|string|max:20|unique:sinhvien,ma_sv,' . $id,
            'ho_ten' => 'required|string|max:100',
            'lop' => 'required|string|max:50'
        ]);

        $sinhVien->update([
            'ma_sv' => $request->ma_sv,
            'ho_ten' => $request->ho_ten,
            'lop' => $request->lop
        ]);

        return response()->json([
            'message' => 'Cập nhật sinh viên thành công',
            'data' => $sinhVien
        ]);
    }


    // DELETE /api/sinhvien/{id}
    // Xóa sinh viên
    public function destroy($id)
    {
        $sinhVien = SinhVien::find($id);

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