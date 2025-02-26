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
        Schema::create('prestasi_siswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rombel_id');
            $table->enum('jenis_prestasi', ['1', '2']);
            $table->string('deskripsi', 200);
            $table->timestamps();

            // Jenis Prestasi
            // 1 = Akademik
            // 2 = Non Akademik
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_siswas');
    }
};
