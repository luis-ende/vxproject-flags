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
        Schema::create('vx_flag_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('set null');
            $table->foreignId('id_vx_flag')->constrained('vx_flags')->onDelete('cascade');
            $table->text('notes');
            $table->timestamps();

            $table->unique(['id_vx_flag', 'user_id'], 'id_vx_flag_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vx_flag_notes');
    }
};
