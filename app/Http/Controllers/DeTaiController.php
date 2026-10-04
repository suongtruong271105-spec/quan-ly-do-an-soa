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

    public function show(DeTai $deTai): JsonResponse
    {
        return response()->json([
            'data' => $deTai,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ma_dt' => 'required|string|max:20|unique:detai,ma_dt',
            'ten_dt' => 'required|string|max:255',
            'giang_vien_huong_dan' => 'required|string|max:100',
        ]);

        $deTai = DeTai::create($validated);

        return response()->json([
            'message' => 'Thêm đề tài thành công',
            'data' => $deTai,
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $deTai = DeTai::findOrFail($id);

        $validated = $request->validate([
            'ma_dt' => 'required|string|max:20|unique:detai,ma_dt,' . $id,
            'ten_dt' => 'required|string|max:255',
            'giang_vien_huong_dan' => 'required|string|max:100',
        ]);

        $deTai->update($validated);

        return response()->json([
            'message' => 'Cập nhật đề tài thành công',
            'data' => $deTai,
        ], 200);
    }

   public function destroy($id)
{
    // 1. Tìm đề tài trong Database dựa vào ID
    $detai = \App\Models\DeTai::find($id);

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