<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

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
         RateLimiter::for('create-locations', function ($request) {
            return Limit::perMinute(3)->by($request->ip())->response(function () {
                return response()->json([
                    'error' => [
                        'message' => 'Too many requests. Please try again later.'
                    ]
                ], 429);
            });
        });
    }
}
