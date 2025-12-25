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
        Schema::table('groups', function (Blueprint $table) {
            $table->foreignId('competition_id')->nullable()->constrained()->onDelete('cascade');
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->foreignId('competition_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropConstrainedForeignId('competition_id');
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->dropConstrainedForeignId('competition_id');
        });
    }
};
