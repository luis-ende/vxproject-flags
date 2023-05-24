<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vx_flags', function (Blueprint $table) {
            $table->string('flag_key', 50)->default(Str::random(50));
        });

        DB::statement('UPDATE vx_flags SET flag_key = description');

        Schema::table('vx_flags', function (Blueprint $table) {
            $table->unique('flag_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vx_flags', function (Blueprint $table) {
            $table->dropColumn('flag_key');
        });
    }
};
