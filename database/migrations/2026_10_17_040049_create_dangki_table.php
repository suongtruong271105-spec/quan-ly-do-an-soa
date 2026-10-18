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
    Schema::connection('dangky_conn')->create('dangki', function (Blueprint $table) {
        $table->id();
        $table->string('masv', 20); // Dùng string, KHÔNG dùng foreignId()
        $table->string('madt', 20); // Dùng string, KHÔNG dùng foreignId()
        $table->string('trangthai', 50)->default('Cho Duyet');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dangki');
    }
};
