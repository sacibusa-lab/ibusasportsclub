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
        Schema::create('competition_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competitions')->onDelete('cascade');
            $table->string('registration_code')->nullable()->unique();
            $table->string('team_name');
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->string('status')->default('initiated'); // initiated, phase1_paid, completed
            
            // Phase 1 Details & Payment
            $table->decimal('phase1_amount', 10, 2)->default(0);
            $table->string('phase1_payment_status')->default('pending'); // pending, paid
            $table->string('phase1_payment_ref')->nullable()->unique();
            $table->timestamp('phase1_paid_at')->nullable();
            $table->json('phase1_data')->nullable();

            // Phase 2 Details & Payment
            $table->decimal('phase2_amount', 10, 2)->default(0);
            $table->string('phase2_payment_status')->default('pending'); // pending, paid
            $table->string('phase2_payment_ref')->nullable()->unique();
            $table->timestamp('phase2_paid_at')->nullable();
            $table->json('phase2_data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_registrations');
    }
};
