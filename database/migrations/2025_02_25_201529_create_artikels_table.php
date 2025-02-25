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
        Schema::create('artikels', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_publish');
            $table->unsignedBigInteger('kategori_id');
            $table->string('judul');
            $table->string('slug');
            $table->text('content')->nullable();
            $table->string('image')->default('image.jpg');
            $table->enum('status', ['publish', 'archived'])->default('publish');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};
