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
        Schema::create('k13_nilai_pts_pas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembelajaran_id');
            $table->unsignedBigInteger('rombel_id');
            $table->integer('nilai_pts');
            $table->integer('nilai_pas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k13_nilai_pts_pas');
    }
};
