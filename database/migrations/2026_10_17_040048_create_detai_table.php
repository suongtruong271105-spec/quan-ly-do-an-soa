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
    Schema::connection('detai_conn')->create('detai', function (Blueprint $table) {
        $table->string('madt', 20)->primary();
        $table->string('tendt', 255);
        $table->string('giangvien', 100)->nullable();
        $table->integer('soluong_toida')->default(1);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detai');
    }
};
