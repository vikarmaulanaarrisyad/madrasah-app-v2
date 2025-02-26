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
        Schema::create('k13_butir_sikaps', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_kompetensi', ['1', '2']);
            $table->string('kode', 10)->unique();
            $table->string('butir_sikap');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('k13_butir_sikaps');
    }
};
