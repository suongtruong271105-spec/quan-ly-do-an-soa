<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SinhVien;
use App\Models\DeTai;
use App\Models\DangKi;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tự động tạo 10 Sinh viên sử dụng Factory
        $sinhViens = SinhVien::factory(10)->create();

        // 2. Tự động tạo 10 Đề tài sử dụng Factory
        $deTais = DeTai::factory(10)->create();

        // 3. Tự động tạo các lượt đăng ký tương ứng (ví dụ: mỗi sinh viên đăng ký 1 đề tài ngẫu nhiên)
        foreach ($sinhViens as $index => $sv) {
            $dt = $deTais[$index % count($deTais)]; // Lấy xoay vòng đề tài cho sinh viên
            
            DangKi::create([
                'masv' => $sv->masv,
                'madt' => $dt->madt,
                'trangthai' => $index % 2 == 0 ? 'Da Duyet' : 'Cho Duyet',
            ]);
        }
    }
}