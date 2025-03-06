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
        Schema::create('jam_kerja_khususes', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique(); // Tanggal khusus, misalnya Ramadhan
            $table->time('jam_masuk'); // Jam masuk khusus
            $table->time('jam_keluar'); // Jam keluar khusus
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_kerja_khususes');
    }
};
