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
        Schema::create('jam_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tahun_pelajaran_id');
            $table->enum('jenis', ['upacara', 'pembelajaran', 'istirahat', 'pembiasaan']);
            $table->integer('jam_ke');
            $table->time('mulai');
            $table->integer('durasi');
            $table->time('selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_pelajarans');
    }
};
