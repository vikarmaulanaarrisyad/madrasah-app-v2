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
        Schema::create('k13_kd_mapels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mata_pelajaran_id');
            $table->string('tingkatan_kelas', 2);
            $table->enum('jenis_kompetensi', ['1', '2', '3', '4']);
            $table->enum('semester', ['1', '2']);
            $table->string('kode_kd', 10);
            $table->string('kompetensi_dasar');
            $table->string('ringkasan_kompetensi', 150);
            $table->timestamps();

            // Jenis Kompetensi
            // 1 = Sikap Spiritual
            // 2 = Sikap Sosial
            // 3 = Pengetahuan
            // 4 = Keterampilan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k13_kd_mapels');
    }
};
