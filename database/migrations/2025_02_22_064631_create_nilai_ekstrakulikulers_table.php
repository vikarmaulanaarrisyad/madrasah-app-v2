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
        Schema::create('nilai_ekstrakulikulers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ekstrakulikuler_id');
            $table->unsignedBigInteger('anggota_ekstrakulikuler_id');
            $table->enum('nilai', ['4', '3', '2', '1']);
            $table->string('deskripsi', 200);
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
        Schema::dropIfExists('nilai_ekstrakulikulers');
    }
};
