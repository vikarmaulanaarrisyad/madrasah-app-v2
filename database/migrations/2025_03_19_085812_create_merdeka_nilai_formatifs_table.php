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
        Schema::create('merdeka_nilai_formatifs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tahun_pelajaran_id');
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('rombel_id');
            $table->unsignedBigInteger('mata_pelajaran_id');
            $table->integer('sum')->default(1);
            $table->text('materi')->nullable();
            $table->integer('nilai')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merdeka_nilai_formatifs');
    }
};
