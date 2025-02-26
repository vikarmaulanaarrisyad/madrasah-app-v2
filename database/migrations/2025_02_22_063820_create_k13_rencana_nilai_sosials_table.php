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
        Schema::create('k13_rencana_nilai_sosials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembelajaran_id');
            $table->unsignedBigInteger('k13_butir_sikap_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k13_rencana_nilai_sosials');
    }
};
