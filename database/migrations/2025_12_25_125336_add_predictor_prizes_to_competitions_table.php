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
        Schema::table('competitions', function (Blueprint $table) {
            $table->string('predictor_prize_1')->nullable()->after('description');
            $table->string('predictor_prize_2')->nullable()->after('predictor_prize_1');
            $table->string('predictor_prize_3')->nullable()->after('predictor_prize_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competitions', function (Blueprint $table) {
            $table->dropColumn(['predictor_prize_1', 'predictor_prize_2', 'predictor_prize_3']);
        });
    }
};
