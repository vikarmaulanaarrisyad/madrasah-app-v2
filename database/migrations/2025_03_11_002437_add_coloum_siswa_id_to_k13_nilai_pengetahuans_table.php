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
        Schema::table('k13_nilai_pengetahuans', function (Blueprint $table) {
            $table->unsignedBigInteger('tahun_pelajaran_id')->after('id')->nullable();
            $table->unsignedBigInteger('siswa_id')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('k13_nilai_pengetahuans', function (Blueprint $table) {
            $table->dropColumn('tahun_pelajaran_id');
            $table->dropColumn('siswa_id');
        });
    }
};
