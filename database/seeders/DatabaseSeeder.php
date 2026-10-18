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
        // 1. Tạo thủ công 2 sinh viên vào DB sinh viên
        SinhVien::create([
            'masv' => 'SV001', 'hoten' => 'Nguyễn Văn A', 'ngaysinh' => '2003-01-01', 'lop' => 'KTPM', 'email' => 'a@gmail.com'
        ]);
        SinhVien::create([
            'masv' => 'SV002', 'hoten' => 'Trần Thị B', 'ngaysinh' => '2003-02-02', 'lop' => 'HTTT', 'email' => 'b@gmail.com'
        ]);

        // 2. Tạo thủ công 2 đề tài vào DB đề tài
        DeTai::create([
            'madt' => 'DT001', 'tendt' => 'Xây dựng web SOA', 'giangvien' => 'Cô giáo X', 'soluong_toida' => 2
        ]);
        DeTai::create([
            'madt' => 'DT002', 'tendt' => 'Nhận diện khuôn mặt', 'giangvien' => 'Thầy giáo Y', 'soluong_toida' => 1
        ]);

        // 3. Tạo dữ liệu đăng ký vào DB đăng ký
        DangKi::create(['masv' => 'SV001', 'madt' => 'DT001', 'trangthai' => 'Da Duyet']);
        DangKi::create(['masv' => 'SV002', 'madt' => 'DT002', 'trangthai' => 'Cho Duyet']);
    }
}