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
            if (!Schema::hasColumn('matches', 'referee_ar1_id')) {
                $table->unsignedBigInteger('referee_ar1_id')->nullable();
                $table->foreign('referee_ar1_id')->references('id')->on('referees')->onDelete('set null');
            }
            if (!Schema::hasColumn('matches', 'referee_ar2_id')) {
                $table->unsignedBigInteger('referee_ar2_id')->nullable();
                $table->foreign('referee_ar2_id')->references('id')->on('referees')->onDelete('set null');
            }
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
