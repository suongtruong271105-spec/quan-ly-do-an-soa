<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
       public function up(): void
{
    Schema::create('dangky', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('sinhvien_id');
        $table->unsignedBigInteger('detai_id');
        $table->float('diem')->nullable(); 
        $table->timestamps();

        // Khóa ngoại liên kết 3 bảng
        $table->foreign('sinhvien_id')->references('id')->on('sinhvien')->onDelete('cascade');
        $table->foreign('detai_id')->references('id')->on('detai')->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dangky');
    }

};
