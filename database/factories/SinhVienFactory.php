<?php

namespace Database\Factories;

use App\Models\SinhVien;
use Illuminate\Database\Eloquent\Factories\Factory;

class SinhVienFactory extends Factory
{
    protected $model = SinhVien::class;

    // Khai báo biến đếm tĩnh để lấy tên tuần tự không bị trùng
    protected static int $index = 0;

    public function definition(): array
    {
        $danhSachTenSV = [
            'Nguyễn Văn An', 'Trần Thị Bình', 'Lê Hoàng Cường', 'Phạm Thị Dung',
            'Hoàng Văn Bảo', 'Vũ Thị Phương', 'Đặng Tuấn Hải', 'Bùi Thị Hoa',
            'Đỗ Minh Trí', 'Ngô Ngọc Lan', 'Dương Văn Kiên', 'Lý Thị Mai',
            'Đinh Văn Nam', 'Lâm Thị Hương', 'Bùi Đức Long', 'Cao Thị Thảo',
            'Hồ Tấn Phát', 'Vương Mỹ Tâm', 'Phan Quốc Huy', 'Trương Diệu Anh'
        ];

        // Lấy tên theo thứ tự vòng tròn từ mảng để các tên không bị lặp lại
        $ten = $danhSachTenSV[self::$index % count($danhSachTenSV)];
        self::$index++;

        return [
            'masv' => $this->faker->unique()->numerify('SV####'),
            'hoten' => $ten,
            'lop' => $this->faker->randomElement(['KTPM1', 'KTPM2', 'CNTT1', 'HTTT']),
        ];
    }
}