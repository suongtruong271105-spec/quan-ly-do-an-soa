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
        return [
            'ma_dt' => $this->faker->unique()->numerify('DT####'),
            'ten_dt' => $this->faker->realText(50), // Tạo câu văn bản ngẫu nhiên dài khoảng 50 ký tự
            'giang_vien_huong_dan' => $this->faker->name(),
        ];
    }
}
