<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        // Share settings globally
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $siteSettings = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
                
                // Defaults
                $defaults = [
                    'site_name' => 'LOCAL CHAMPIONSHIP',
                    'site_short_name' => 'LC',
                    'contact_email' => 'admin@tournament.com',
                    'primary_color' => '#3d195b',
                    'secondary_color' => '#00ff85',
                    'accent_color' => '#ff005a',
                    'current_season' => date('Y'),
                    'footer_text' => 'Local Community Football Championship. Built with Laravel. Not affiliated with the Premier League.',
                    'copyright_text' => '© ' . date('Y') . ' Local Community Football Championship.',
                ];

                $siteSettings = array_merge($defaults, $siteSettings);
                view()->share('siteSettings', $siteSettings);
            }
        } catch (\Exception $e) {
            // Silently fail if DB not set up
        }

        view()->composer('layout', function ($view) {
            $view->with('globalSponsors', \App\Models\Sponsor::where('active', true)->orderBy('order', 'desc')->get());
            $view->with('globalInterviews', \App\Models\Interview::orderBy('display_order', 'asc')->orderBy('created_at', 'desc')->limit(8)->get());
        });

        view()->composer('admin.layout', function ($view) {
            $view->with('pendingCommentsCount', \App\Models\Comment::where('is_approved', false)->count());
        });
    }
}
