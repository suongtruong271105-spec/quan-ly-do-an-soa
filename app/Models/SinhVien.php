<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    // Tên connection phải khớp với tên bạn đã khai báo trong config/database.php
    protected $connection = 'sinhvien_conn'; 
    protected $table = 'sinhvien';
    protected $primaryKey = 'masv'; // Khai báo khóa chính nếu không dùng id mặc định
    public $incrementing = false; // Báo cho Laravel biết khóa chính không phải số tự tăng
    protected $keyType = 'string';
    
    protected $fillable = ['masv', 'hoten', 'ngaysinh', 'lop', 'email'];
}