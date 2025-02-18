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
        Schema::create('absensi_gurus', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('guru_id');
            $table->date('tgl_presensi');
            $table->time('waktu_masuk')->nullable(); // Waktu masuk
            $table->time('waktu_keluar')->nullable(); // Waktu keluar
            $table->string('status');
            $table->boolean('is_holiday')->default(false); // untuk hari libur
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_gurus');
    }
};
