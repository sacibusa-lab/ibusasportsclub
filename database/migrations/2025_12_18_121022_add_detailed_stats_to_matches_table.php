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
            $table->integer('home_possession')->default(50);
            $table->integer('away_possession')->default(50);
            $table->integer('home_shots')->default(0);
            $table->integer('away_shots')->default(0);
            $table->text('home_scorers')->nullable();
            $table->text('away_scorers')->nullable();
            $table->text('report')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            //
        });
    }
};
