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
        Schema::create('jurnal_gurus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tahun_pelajaran_id');
            $table->unsignedInteger('rombel_id');
            $table->unsignedInteger('guru_id');
            $table->unsignedInteger('mata_pelajaran_id');
            $table->date('tanggal');
            $table->integer('pembelajaran_ke')->nullable();
            $table->text('tema')->nullable();
            $table->text('tujuan_pembelajaran')->nullable();
            $table->text('materi');
            $table->text('penilaian')->nullable();
            $table->text('metode_pembelajaran')->nullable();
            $table->text('evaluasi')->nullable();
            $table->text('refleksi')->nullable();
            $table->text('tugas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_gurus');
    }
};
