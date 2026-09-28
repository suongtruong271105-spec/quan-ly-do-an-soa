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
       return [
            'ma_sv' => $this->faker->unique()->numerify('SV####'), // Tạo chuỗi dạng SV0123
            'ho_ten' => $this->faker->name(), // Tên người ngẫu nhiên
            'lop' => $this->faker->randomElement(['KTPM1', 'KTPM2', 'CNTT1', 'HTTT']), // Chọn ngẫu nhiên 1 lớp
        ];
    }
}
