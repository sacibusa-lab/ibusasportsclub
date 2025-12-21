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
        Schema::table('match_lineups', function (Blueprint $table) {
            $table->string('position_key')->nullable()->after('shirt_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_lineups', function (Blueprint $table) {
            $table->dropColumn('position_key');
        });
    }
};
