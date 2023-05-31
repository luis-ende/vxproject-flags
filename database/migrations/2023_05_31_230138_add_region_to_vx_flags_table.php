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
        Schema::table('vx_flags', function (Blueprint $table) {
            $table->string('region', 2)->nullable();
            $table->index('region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vx_flags', function (Blueprint $table) {
            $table->dropColumn('region');
            $table->dropIndex('region');
        });
    }
};
