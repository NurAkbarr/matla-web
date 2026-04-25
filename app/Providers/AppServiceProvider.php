<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

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
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Rate Limiter for PMB Registration (Anti-Spam)
        RateLimiter::for('registration', function (Request $request) {
            return Limit::perHour(3)->by($request->ip())->response(function (Request $request, array $headers) {
                return back()->with('error', 'Terlalu banyak mencoba pendaftaran. Silakan coba lagi dalam 1 jam.');
            });
        });

        // Rate Limiter for Contact Messages
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perHour(5)->by($request->ip())->response(function (Request $request, array $headers) {
                return back()->with('error', 'Batas pengiriman pesan tercapai. Silakan coba lagi nanti.');
            });
        });

        // Global/Login Rate Limiter
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function (Request $request, array $headers) {
                return back()->with('error', 'Terlalu banyak percobaan login. Keamanan mendeteksi aktivitas mencurigakan, silakan tunggu 1 menit.');
            });
        });
    }
}
