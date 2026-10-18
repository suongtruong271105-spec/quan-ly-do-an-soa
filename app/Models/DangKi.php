<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DangKi extends Model
{
    protected $connection = 'dangky_conn'; 
    protected $table = 'dangki';
    // Bảng này có thể dùng id tự tăng mặc định của Laravel
    protected $fillable = ['masv', 'madt', 'trangthai'];
}