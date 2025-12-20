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
            $table->integer('home_corners')->default(0)->after('away_shots');
            $table->integer('away_corners')->default(0)->after('home_corners');
            $table->integer('home_offsides')->default(0)->after('away_corners');
            $table->integer('away_offsides')->default(0)->after('home_offsides');
            $table->integer('home_fouls')->default(0)->after('away_offsides');
            $table->integer('away_fouls')->default(0)->after('home_fouls');
            $table->integer('home_throw_ins')->default(0)->after('away_fouls');
            $table->integer('away_throw_ins')->default(0)->after('home_throw_ins');
            $table->integer('home_saves')->default(0)->after('away_throw_ins');
            $table->integer('away_saves')->default(0)->after('home_saves');
            $table->integer('home_goal_kicks')->default(0)->after('away_saves');
            $table->integer('away_goal_kicks')->default(0)->after('home_goal_kicks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn([
                'home_corners', 'away_corners', 
                'home_offsides', 'away_offsides',
                'home_fouls', 'away_fouls',
                'home_throw_ins', 'away_throw_ins',
                'home_saves', 'away_saves',
                'home_goal_kicks', 'away_goal_kicks'
            ]);
        });
    }
};
