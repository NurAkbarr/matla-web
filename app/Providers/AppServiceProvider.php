<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Masbug\Flysystem\GoogleDriveAdapter;
use League\Flysystem\Filesystem;

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

        // ===== Google Drive Filesystem Driver =====
        try {
            Storage::extend('google', function ($app, $config) {
                $client = new GoogleClient();
                $client->setClientId($config['clientId']);
                $client->setClientSecret($config['clientSecret']);
                $client->refreshToken($config['refreshToken']);
                $client->addScope(GoogleDrive::DRIVE);

                $service = new GoogleDrive($client);
                $options = [];
                if (isset($config['folder'])) {
                    $options['sharedFolderId'] = $config['folder'];
                }
                $adapter = new GoogleDriveAdapter($service, null, $options);

                return new \Illuminate\Filesystem\FilesystemAdapter(
                    new Filesystem($adapter),
                    $adapter,
                    $config
                );
            });
        } catch (\Exception $e) {
            // Silently fail if credentials are missing (e.g. during CI/testing)
            \Illuminate\Support\Facades\Log::warning('Google Drive driver failed to register: ' . $e->getMessage());
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
