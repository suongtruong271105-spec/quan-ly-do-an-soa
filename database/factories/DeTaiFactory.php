<?php

namespace Database\Factories;

use App\Models\DeTai;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeTai>
 */
class DeTaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
{
    $danhSachDeTai = [
        'Xây dựng website bán hàng trực tuyến',
        'Phần mềm quản lý thư viện trường học',
        'Ứng dụng điểm danh sinh viên bằng QR Code',
        'Hệ thống nhận diện khuôn mặt bằng AI',
        'Website đặt vé xem phim trực tuyến',
        'Ứng dụng quản lý nhà trọ và tính tiền điện'
    ];

    $danhSachGiangVien = [
        'PGS.TS. Nguyễn Hữu Dũng', 'TS. Trần Lê Hùng', 'ThS. Lê Thị Ái',
        'ThS. Phạm Tuấn Anh', 'TS. Hoàng Đăng Khoa'
    ];

    return [
        'ma_dt' => $this->faker->unique()->numerify('DT####'),
        'ten_dt' => $this->faker->randomElement($danhSachDeTai), 
        'giang_vien_huong_dan' => $this->faker->randomElement($danhSachGiangVien),
    ];
}
}
