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
        Schema::create('nilai_raports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tahun_pelajaran_id');
            $table->unsignedBigInteger('pembelajaran_id');
            $table->unsignedBigInteger('rombel_id');
            $table->integer('kkm');
            $table->integer('nilai_pengetahuan');
            $table->enum('predikat_pengetahuan', ['A', 'B', 'C', 'D']);
            $table->integer('nilai_keterampilan');
            $table->enum('predikat_keterampilan', ['A', 'B', 'C', 'D']);
            $table->enum('nilai_spiritual', ['1', '2', '3', '4']);
            $table->enum('nilai_sosial', ['1', '2', '3', '4']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_raports');
    }
};
