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
    Schema::connection('sinhvien_conn')->create('sinhvien', function (Blueprint $table) {
        $table->string('masv', 20)->primary();
        $table->string('hoten', 100);
        $table->date('ngaysinh')->nullable();
        $table->string('lop', 50)->nullable();
        $table->string('email', 100)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sinhvien');
    }
};
