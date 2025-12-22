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
        if (!Schema::hasColumn('referees', 'has_fifa_badge')) {
            Schema::table('referees', function (Blueprint $table) {
                $table->boolean('has_fifa_badge')->default(false)->after('name');
            });
        }

        if (!Schema::hasColumn('matches', 'referee_id')) {
            Schema::table('matches', function (Blueprint $table) {
                $table->unsignedBigInteger('referee_id')->nullable()->after('venue');
                $table->foreign('referee_id')->references('id')->on('referees')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referees', function (Blueprint $table) {
            $table->dropColumn('has_fifa_badge');
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['referee_id']);
            $table->dropColumn('referee_id');
        });
    }
};
