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
        Schema::table('story_items', function (Blueprint $table) {
            $table->text('caption')->nullable()->after('link_url');
            $table->string('caption_color', 7)->nullable()->default('#FFFFFF')->after('caption');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('story_items', function (Blueprint $table) {
            $table->dropColumn(['caption', 'caption_color']);
        });
    }
};
