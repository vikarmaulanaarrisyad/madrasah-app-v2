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
        Schema::create('k13_nilai_keterampilans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('k13_rencana_nilai_keterampilan_id');
            $table->unsignedBigInteger('rombel_id');
            $table->integer('nilai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k13_nilai_keterampilans');
    }
};
