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
        Schema::table('matches', function (Blueprint $table) {
            $table->foreignId('referee_ar1_id')->nullable()->constrained('referees')->onDelete('set null');
            $table->foreignId('referee_ar2_id')->nullable()->constrained('referees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['referee_ar1_id']);
            $table->dropForeign(['referee_ar2_id']);
            $table->dropColumn(['referee_ar1_id', 'referee_ar2_id']);
        });
    }
};
