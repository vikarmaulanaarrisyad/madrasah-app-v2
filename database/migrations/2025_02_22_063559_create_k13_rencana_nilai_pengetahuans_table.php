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
        Schema::create('k13_rencana_nilai_pengetahuans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembelajaran_id');
            $table->unsignedBigInteger('k13_kd_mapel_id');
            $table->string('kode_penilaian', 4);
            $table->enum('teknik_penilaian', ['1', '2', '3']);
            $table->integer('bobot_teknik_penilaian');
            $table->timestamps();

            // Teknik Penilaian
            // 1 = Tes Tulis
            // 2 = Tes Lisan
            // 3 = Penugasan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k13_rencana_nilai_pengetahuans');
    }
};
