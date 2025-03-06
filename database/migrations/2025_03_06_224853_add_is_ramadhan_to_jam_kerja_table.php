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
        Schema::table('jam_kerjas', function (Blueprint $table) {
            $table->boolean('is_ramadhan')->default(false); // Menandakan apakah jam kerja untuk bulan Ramadhan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jam_kerjas', function (Blueprint $table) {
            $table->dropColumn('is_ramadhan');
        });
    }
};
