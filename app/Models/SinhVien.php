<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    use HasFactory;

    protected $table = 'sinhvien';
    
    // Các cột được phép thêm dữ liệu
    protected $fillable = ['ma_sv', 'ho_ten', 'lop'];

    // Mối quan hệ: 1 sinh viên có thể có nhiều lượt đăng ký
    public function dangKy()
    {
        return $this->hasMany(DangKy::class, 'sinhvien_id', 'id');
    }
}
