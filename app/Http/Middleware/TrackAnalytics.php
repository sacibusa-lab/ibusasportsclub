<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackAnalytics
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Exclude admin routes, API, debugbar, and storage
        if ($request->is('admin*') || $request->is('api*') || $request->is('_debugbar*') || $request->is('storage*')) {
            return $next($request);
        }

        // Check for IP Whitelist from DB Settings
        $ip = $request->ip();
        $whitelist = \App\Models\Setting::where('key', 'analytics_whitelist_ips')->value('value');
        if ($whitelist) {
            $ips = array_map('trim', explode(',', $whitelist));
            if (in_array($ip, $ips)) {
                return $next($request);
            }
        }

        // Only track GET requests for page views
        if (!$request->isMethod('GET')) {
            return $next($request);
        }

        try {
            // Basic Geolocation Logic (IP API)
            // In a real production app, use a local database (like MaxMind) or a paid service to avoid rate limits
            $ip = $request->ip();
            $country = null;
            $city = null;

            // Simple check to avoid calling API for local IPs
            if ($ip !== '127.0.0.1' && $ip !== '::1') {
                $geoData = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}?fields=country,city"));
                if ($geoData) {
                    $country = $geoData->country ?? null;
                    $city = $geoData->city ?? null;
                }
            }

            DB::table('analytics_visits')->insert([
                'ip_address' => $ip,
                'url' => $request->path(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'country' => $country,
                'city' => $city,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Fail silently to avoid breaking the site if DB fails
        }

        return $next($request);
    }
}
