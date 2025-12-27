<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert competitions to InnoDB to support foreign keys
        DB::statement('ALTER TABLE competitions ENGINE = InnoDB');
        
        // Ensure other key tables are also InnoDB
        DB::statement('ALTER TABLE groups ENGINE = InnoDB');
        DB::statement('ALTER TABLE matches ENGINE = InnoDB');
        DB::statement('ALTER TABLE teams ENGINE = InnoDB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert engine changes
    }
};
