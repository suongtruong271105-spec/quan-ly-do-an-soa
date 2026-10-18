<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DeTai extends Model
{
    protected $connection = 'detai_conn'; 
    protected $table = 'detai';
    protected $primaryKey = 'madt';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['madt', 'tendt', 'giangvien', 'soluong_toida'];
}