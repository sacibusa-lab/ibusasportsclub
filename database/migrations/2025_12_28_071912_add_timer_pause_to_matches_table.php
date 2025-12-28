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
            $table->boolean('is_paused')->default(false)->after('started_at');
            $table->timestamp('paused_at')->nullable()->after('is_paused');
            $table->integer('total_paused_seconds')->default(0)->after('paused_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['is_paused', 'paused_at', 'total_paused_seconds']);
        });
    }
};
