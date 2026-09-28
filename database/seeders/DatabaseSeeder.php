<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SinhVien;
use App\Models\DeTai;
use App\Models\DangKy;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
  public function run(): void
    {
        // Tạo 10 Sinh viên và 5 Đề tài
        SinhVien::factory(10)->create();
        DeTai::factory(5)->create();

        // Lấy tất cả sinh viên và đề tài vừa tạo ra
        $sinhViens = SinhVien::all();
        $deTais = DeTai::all();

        // Duyệt qua từng sinh viên, lấy ngẫu nhiên 1 đề tài để gán vào bảng đăng ký
        foreach ($sinhViens as $sv) {
            DangKy::create([
                'sinhvien_id' => $sv->id,
                'detai_id' => $deTais->random()->id,
                'diem' => rand(50, 100) / 10, // Tạo điểm ngẫu nhiên từ 5.0 đến 10.0
            ]);
        }
    }
}
