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
        Schema::create('ektrakurikulers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tahun_pelajaran_id');
            $table->unsignedInteger('guru_id');
            $table->string('nama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ektrakurikulers');
    }
};
