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
        Schema::disableForeignKeyConstraints();

        Schema::table('sponsors', function (Blueprint $table) {
            if (!Schema::hasColumn('sponsors', 'competition_id')) {
                $table->unsignedBigInteger('competition_id')->nullable()->after('id');
            }
        });

        Schema::table('sponsors', function (Blueprint $table) {
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropForeign(['competition_id']);
            $table->dropColumn('competition_id');
        });
    }
};
