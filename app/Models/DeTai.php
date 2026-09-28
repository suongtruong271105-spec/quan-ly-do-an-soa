<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeTai extends Model
{
    use HasFactory;

    protected $table = 'detai';

    protected $fillable = ['ma_dt', 'ten_dt', 'giang_vien_huong_dan'];

    // Mối quan hệ: 1 đề tài có thể được nhiều sinh viên đăng ký
    public function dangKy()
    {
        return $this->hasMany(DangKy::class, 'detai_id', 'id');
    }
}
