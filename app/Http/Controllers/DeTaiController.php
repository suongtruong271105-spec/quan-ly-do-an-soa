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

    public function destroy(DeTai $deTai): JsonResponse
    {
        $deTai->delete();

        return response()->json([
            'message' => 'Xóa đề tài thành công.',
        ]);
    }
}