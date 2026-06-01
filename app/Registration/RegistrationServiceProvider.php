<?php

namespace App\Registration;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class RegistrationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load custom routes
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // Load custom database migrations
        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        // Load custom views under the 'registration' namespace
        $this->loadViewsFrom(__DIR__ . '/Views', 'registration');

        // Seed default settings for registration if not exists
        try {
            if (Schema::hasTable('settings')) {
                $defaults = [
                    'registration_instructions' => 'Welcome to the Tournament Registration portal! To participate in the competition, you need to complete registration in two phases. Phase 1 reserves your slot (Participation Fee), and Phase 2 allows you to upload your team roster and players list (Full Tournament Fee). Make sure to enter accurate contact details as confirmation and login codes will be sent to your email.',
                    'registration_phase1_fee' => '5000',
                    'registration_phase2_fee' => '15000',
                    'registration_phase1_active' => '1',
                    'registration_phase2_active' => '1',
                ];

                foreach ($defaults as $key => $value) {
                    if (!Setting::where('key', $key)->exists()) {
                        Setting::create(['key' => $key, 'value' => $value]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail if database is not set up
        }
    }
}
