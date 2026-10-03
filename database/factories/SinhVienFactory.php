<?php

namespace Database\Factories;

use App\Models\SinhVien;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SinhVien>
 */
class SinhVienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    $danhSachTenSV = [
        'Nguyễn Văn An', 'Trần Thị Bình', 'Lê Hoàng Cường', 'Phạm Thị Dung',
        'Hoàng Văn Bảo', 'Vũ Thị Phương', 'Đặng Tuấn Hải', 'Bùi Thị Hoa',
        'Đỗ Minh Trí', 'Ngô Ngọc Lan', 'Dương Văn Kiên', 'Lý Thị Mai'
    ];

    return [
        'ma_sv' => $this->faker->unique()->numerify('SV####'),
        'ho_ten' => $this->faker->randomElement($danhSachTenSV),
        'lop' => $this->faker->randomElement(['KTPM1', 'KTPM2', 'CNTT1', 'HTTT']),
    ];
}
}
