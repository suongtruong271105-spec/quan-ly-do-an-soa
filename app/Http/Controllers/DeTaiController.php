<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDeTaiRequest;
use App\Http\Requests\UpdateDeTaiRequest;
use App\Models\DeTai;
use Illuminate\Http\JsonResponse;
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
        $request->validate([
            'ma_dt' => 'required|string|max:20|unique:detai,ma_dt',
            'ten_dt' => 'required|string|max:255',
            'giang_vien_hd' => 'nullable|string|max:100',
        ]);

        $deTai = DeTai::create([
            'ma_dt' => $request->ma_dt,
            'ten_dt' => $request->ten_dt,
            'giang_vien_hd' => $request->giang_vien_hd,
        ]);

        return response()->json([
            'message' => 'Thêm đề tài thành công',
            'data' => $deTai
        ], 201);
    }

   public function update(Request $request, $id)
    {
        $deTai = DeTai::findOrFail($id);

        $request->validate([
            'ma_dt' => 'required|string|max:20|unique:detai,ma_dt,' . $id . ',ma_dt',
            'ten_dt' => 'required|string|max:255',
            'giang_vien_hd' => 'nullable|string|max:100',
        ]);

        $deTai->update([
            'ma_dt' => $request->ma_dt,
            'ten_dt' => $request->ten_dt,
            'giang_vien_hd' => $request->giang_vien_hd,
        ]);

        return response()->json([
            'message' => 'Cập nhật đề tài thành công',
            'data' => $deTai
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