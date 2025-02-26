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
        Schema::create('k13_deskripsi_nilai_siswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembelajaran_id');
            $table->unsignedBigInteger('k13_nilai_akhir_raport_id');
            $table->string('deskripsi_pengetahuan', 200);
            $table->string('deskripsi_keterampilan', 200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k13_deskripsi_nilai_siswas');
    }
};
