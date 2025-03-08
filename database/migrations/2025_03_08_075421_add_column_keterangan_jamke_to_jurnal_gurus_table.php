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
        Schema::table('jurnal_gurus', function (Blueprint $table) {
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('jadwal_pelajaran_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurnal_gurus', function (Blueprint $table) {
            $table->dropColumn([
                'keterangan',
                'jadwal_pelajaran_id'
            ]);
        });
    }
};
