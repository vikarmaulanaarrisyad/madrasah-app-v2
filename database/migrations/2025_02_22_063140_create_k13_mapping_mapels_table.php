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
        Schema::create('k13_mapping_mapels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mata_pelajaran_id');
            $table->enum('kelompok', ['A', 'B']);
            $table->integer('nomor_urut');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k13_mapping_mapels');
    }
};
