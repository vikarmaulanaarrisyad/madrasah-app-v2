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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu_title');
            $table->string('menu_url');
            $table->enum('menu_target', ['_blank', '_self', '_parent', '_top'])->default('_self');
            $table->string('menu_type');
            $table->unsignedBigInteger('menu_parent_id')->default(0);
            $table->unsignedBigInteger('menu_position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
