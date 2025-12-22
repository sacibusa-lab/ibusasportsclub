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

        // Only track GET requests for page views
        if (!$request->isMethod('GET')) {
            return $next($request);
        }

        try {
            DB::table('analytics_visits')->insert([
                'ip_address' => $request->ip(),
                'url' => $request->path(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Fail silently to avoid breaking the site if DB fails
        }

        return $next($request);
    }
}
