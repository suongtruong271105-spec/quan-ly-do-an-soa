<?php

namespace Database\Factories;

use App\Models\DeTai;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeTaiFactory extends Factory
{
    protected $model = DeTai::class;

    // Biến đếm tĩnh để phân phối đều dữ liệu không bị trùng
    protected static int $index = 0;

    public function definition(): array
    {
        $danhSachDeTai = [
            'Xây dựng website bán hàng trực tuyến',
            'Phần mềm quản lý thư viện trường học',
            'Ứng dụng điểm danh sinh viên bằng QR Code',
            'Hệ thống nhận diện khuôn mặt bằng AI',
            'Website đặt vé xem phim trực tuyến',
            'Ứng dụng quản lý nhà trọ và tính tiền điện',
            'Hệ thống quản lý điểm sinh viên trực tuyến',
            'Phần mềm quản lý kho hàng và bán lẻ',
            'Website tuyển dụng và tìm kiếm việc làm',
            'Ứng dụng đặt lịch khám bệnh trực tuyến'
        ];

        $danhSachGiangVien = [
            'PGS.TS. Nguyễn Hữu Dũng', 'TS. Trần Lê Hùng', 'ThS. Lê Thị Ái',
            'ThS. Phạm Tuấn Anh', 'TS. Hoàng Đăng Khoa', 'PGS.TS. Lê Văn Minh',
            'TS. Nguyễn Thị Bích', 'ThS. Trần Văn Nam', 'TS. Đặng Thanh Hằng', 'ThS. Bùi Minh Tuấn'
        ];

        // Lấy dữ liệu tuần tự theo index để không bị trùng lặp
        $tendt = $danhSachDeTai[self::$index % count($danhSachDeTai)];
        $giangvien = $danhSachGiangVien[self::$index % count($danhSachGiangVien)];
        self::$index++;

        return [
            'madt' => $this->faker->unique()->numerify('DT####'),
            'tendt' => $tendt,
            'giangvien' => $giangvien,
        ];
    }
}