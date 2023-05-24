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
        Schema::create('flags_importaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_flag_group')->constrained('flag_groups')->cascadeOnDelete();
            $table->json('import_log');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flags_importaciones');
    }
};
