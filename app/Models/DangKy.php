<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DangKy extends Model
{
    use HasFactory;

    protected $table = 'dangky';

    protected $fillable = ['sinhvien_id', 'detai_id', 'diem'];

    // Mối quan hệ ngược lại: 1 lượt đăng ký thuộc về 1 sinh viên và 1 đề tài
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'sinhvien_id', 'id');
    }

    public function deTai()
    {
        return $this->belongsTo(DeTai::class, 'detai_id', 'id');
    }
}
