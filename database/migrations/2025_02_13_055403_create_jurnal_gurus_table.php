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
        Schema::create('jurnal_gurus', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('guru_id');
            $table->unsignedInteger('mata_pelajaran_id');
            $table->unsignedInteger('kelas_id');
            $table->date('tanggal');
            $table->text('materi');
            $table->text('cp')->nullable()->default('-');
            $table->text('tugas')->nullable()->default('-');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_gurus');
    }
};
