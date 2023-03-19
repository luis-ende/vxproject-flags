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
        Schema::create('vx_flag_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_vx_flag')->constrained('vx_flags')->cascadeOnDelete();
            $table->json('attributes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vx_flag_attributes');
    }
};
