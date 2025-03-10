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
        Schema::table('k13_nilai_pts_pas', function (Blueprint $table) {
            $table->unsignedBigInteger('siswa_id')->after('tahun_pelajaran_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('k13_nilai_pts_pas', function (Blueprint $table) {
            $table->dropColumn('siswa_id');
        });
    }
};
