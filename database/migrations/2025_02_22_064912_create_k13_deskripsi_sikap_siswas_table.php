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
        Schema::create('k13_deskripsi_sikap_siswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rombel_id')->unsigned();
            $table->enum('nilai_spiritual', ['1', '2', '3', '4']);
            $table->string('deskripsi_spiritual');
            $table->enum('nilai_sosial', ['1', '2', '3', '4']);
            $table->string('deskripsi_sosial');
            $table->timestamps();

            // Nilai
            // 1 = Kurang
            // 2 = Cukup
            // 3 = Baik
            // 4 = Sangat Baik
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k13_deskripsi_sikap_siswas');
    }
};
