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
        // First, update any existing NULL values to 0
        \DB::table('matches')->whereNull('home_score')->update(['home_score' => 0]);
        \DB::table('matches')->whereNull('away_score')->update(['away_score' => 0]);
        \DB::table('matches')->whereNull('home_shots')->update(['home_shots' => 0]);
        \DB::table('matches')->whereNull('away_shots')->update(['away_shots' => 0]);
        \DB::table('matches')->whereNull('home_corners')->update(['home_corners' => 0]);
        \DB::table('matches')->whereNull('away_corners')->update(['away_corners' => 0]);
        \DB::table('matches')->whereNull('home_offsides')->update(['home_offsides' => 0]);
        \DB::table('matches')->whereNull('away_offsides')->update(['away_offsides' => 0]);
        \DB::table('matches')->whereNull('home_fouls')->update(['home_fouls' => 0]);
        \DB::table('matches')->whereNull('away_fouls')->update(['away_fouls' => 0]);
        \DB::table('matches')->whereNull('home_throw_ins')->update(['home_throw_ins' => 0]);
        \DB::table('matches')->whereNull('away_throw_ins')->update(['away_throw_ins' => 0]);
        \DB::table('matches')->whereNull('home_saves')->update(['home_saves' => 0]);
        \DB::table('matches')->whereNull('away_saves')->update(['away_saves' => 0]);
        \DB::table('matches')->whereNull('home_goal_kicks')->update(['home_goal_kicks' => 0]);
        \DB::table('matches')->whereNull('away_goal_kicks')->update(['away_goal_kicks' => 0]);
        \DB::table('matches')->whereNull('home_free_kicks')->update(['home_free_kicks' => 0]);
        \DB::table('matches')->whereNull('away_free_kicks')->update(['away_free_kicks' => 0]);
        \DB::table('matches')->whereNull('home_missed_chances')->update(['home_missed_chances' => 0]);
        \DB::table('matches')->whereNull('away_missed_chances')->update(['away_missed_chances' => 0]);

        // Then, modify columns to have a default value of 0 and be NOT NULL
        Schema::table('matches', function (Blueprint $table) {
            $table->integer('home_score')->default(0)->nullable(false)->change();
            $table->integer('away_score')->default(0)->nullable(false)->change();
            $table->integer('home_shots')->default(0)->nullable(false)->change();
            $table->integer('away_shots')->default(0)->nullable(false)->change();
            $table->integer('home_corners')->default(0)->nullable(false)->change();
            $table->integer('away_corners')->default(0)->nullable(false)->change();
            $table->integer('home_offsides')->default(0)->nullable(false)->change();
            $table->integer('away_offsides')->default(0)->nullable(false)->change();
            $table->integer('home_fouls')->default(0)->nullable(false)->change();
            $table->integer('away_fouls')->default(0)->nullable(false)->change();
            $table->integer('home_throw_ins')->default(0)->nullable(false)->change();
            $table->integer('away_throw_ins')->default(0)->nullable(false)->change();
            $table->integer('home_saves')->default(0)->nullable(false)->change();
            $table->integer('away_saves')->default(0)->nullable(false)->change();
            $table->integer('home_goal_kicks')->default(0)->nullable(false)->change();
            $table->integer('away_goal_kicks')->default(0)->nullable(false)->change();
            $table->integer('home_free_kicks')->default(0)->nullable(false)->change();
            $table->integer('away_free_kicks')->default(0)->nullable(false)->change();
            $table->integer('home_missed_chances')->default(0)->nullable(false)->change();
            $table->integer('away_missed_chances')->default(0)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->integer('home_score')->nullable()->change();
            $table->integer('away_score')->nullable()->change();
            // ... (other columns can be reversed as needed, but usually we keep defaults)
        });
    }
};
