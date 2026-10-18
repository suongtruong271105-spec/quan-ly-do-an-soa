<?php

namespace App\Http\Controllers;

use App\Models\DeTai;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeTaiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => DeTai::query()->latest()->get(),
        ]);
    }

    public function show($madt): JsonResponse
    {
        $deTai = DeTai::find($madt);

        if (!$deTai) {
            return response()->json([
                'message' => 'Không tìm thấy đề tài'
            ], 404);
        }

        return response()->json([
            'data' => $deTai,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Trỏ đúng connection topic_conn và tên cột madt
            'madt'          => 'required|string|max:20|unique:detai_conn.detai,madt',
            'tendt'         => 'required|string|max:255',
            'giangvien'     => 'nullable|string|max:100',
            'soluong_toida' => 'nullable|integer|min:1'
        ]);

        $deTai = DeTai::create($validated);

        return response()->json([
            'message' => 'Thêm đề tài thành công',
            'data' => $deTai,
        ], 201);
    }

    public function update(Request $request, $madt): JsonResponse
    {
        $deTai = DeTai::find($madt);

        if (!$deTai) {
            return response()->json(['message' => 'Không tìm thấy đề tài'], 404);
        }

        $validated = $request->validate([
            // Bỏ qua bản ghi hiện tại khi check unique
            'madt'          => 'required|string|max:20|unique:detai_conn.detai,madt,' . $madt . ',madt',
            'tendt'         => 'required|string|max:255',
            'giangvien'     => 'nullable|string|max:100',
            'soluong_toida' => 'nullable|integer|min:1'
        ]);

        $deTai->update($validated);

        return response()->json([
            'message' => 'Cập nhật đề tài thành công',
            'data' => $deTai,
        ], 200);
    }

    public function destroy($madt): JsonResponse
    {
        // 1. Tìm đề tài trong Database dựa vào mã đề tài (khóa chính là chuỗi)
        $detai = DeTai::find($madt);

        // 2. Nếu không tìm thấy, trả về lỗi 404 (Not Found)
        if (!$detai) {
            return response()->json(['message' => 'Không tìm thấy đề tài'], 404);
        }

        // 3. Nếu tìm thấy, THỰC SỰ XÓA nó khỏi Database
        $detai->delete();

        // 4. Báo thành công về cho Frontend (Mã 200 OK)
        return response()->json(['message' => 'Đã xóa đề tài thành công'], 200);
    }
}