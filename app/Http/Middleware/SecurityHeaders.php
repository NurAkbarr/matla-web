<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Add baseline security headers.
     *
     * Notes:
     * - CSP dibuat cukup permisif karena beberapa view memakai inline <style>/<script>
     *   dan resource eksternal (cdnjs + api.qrserver.com). Bisa diperketat bertahap.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Prevent MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Clickjacking protection (CSP frame-ancestors is primary, XFO for legacy)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Reduce referrer leakage
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Limit browser powerful features
        $response->headers->set(
            'Permissions-Policy',
            'accelerometer=(), autoplay=(), camera=(), display-capture=(), encrypted-media=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), midi=(), payment=(), usb=()'
        );

        // Baseline XSS mitigation (legacy; modern browsers rely on CSP)
        $response->headers->set('X-XSS-Protection', '0');

        // Content Security Policy (keep compatible with current blades)
        $cspList = [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com https://www.youtube.com https://s.ytimg.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "img-src 'self' data: https: blob: https://i.ytimg.com",
            "font-src 'self' data: https://fonts.gstatic.com",
            "connect-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com",
            "frame-src 'self' https://www.youtube.com",
            "form-action 'self'",
        ];

        // Add Vite support for local development
        if (app()->environment('local')) {
            $viteUrl = 'http://127.0.0.1:5173 http://localhost:5173';
            $viteWs = 'ws://127.0.0.1:5173 ws://localhost:5173';
            
            $cspList = array_map(function($line) use ($viteUrl, $viteWs) {
                if (str_starts_with($line, 'script-src')) return $line . ' ' . $viteUrl;
                if (str_starts_with($line, 'style-src')) return $line . ' ' . $viteUrl;
                if (str_starts_with($line, 'connect-src')) return $line . ' ' . $viteUrl . ' ' . $viteWs;
                return $line;
            }, $cspList);
        } else {
            $cspList[] = "upgrade-insecure-requests";
        }

        $csp = implode('; ', $cspList);

        // Allow override via env if needed
        $csp = (string) env('SECURITY_CSP', $csp);
        if ($csp !== '') {
            $response->headers->set('Content-Security-Policy', $csp);
        }

        // HSTS only when served over HTTPS
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}

