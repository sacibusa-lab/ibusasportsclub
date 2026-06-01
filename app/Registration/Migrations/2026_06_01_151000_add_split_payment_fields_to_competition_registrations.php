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
        Schema::table('competition_registrations', function (Blueprint $table) {
            $table->decimal('phase2_balance_amount', 10, 2)->default(0)->after('phase2_data');
            $table->string('phase2_balance_status')->default('pending')->after('phase2_balance_amount');
            $table->string('phase2_balance_ref')->nullable()->unique()->after('phase2_balance_status');
            $table->timestamp('phase2_balance_paid_at')->nullable()->after('phase2_balance_ref');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('competition_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'phase2_balance_amount',
                'phase2_balance_status',
                'phase2_balance_ref',
                'phase2_balance_paid_at'
            ]);
        });
    }
};
