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
        $response->headers->set('X-Frame-Options', 'DENY');

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
        $csp = implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'none'",
            // Inline scripts/styles currently used in several blades
            "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline'",
            // Allow QR image / external https images + html-to-image outputs
            "img-src 'self' data: https: blob:",
            "font-src 'self' data: https:",
            "connect-src 'self'",
            "form-action 'self'",
            "upgrade-insecure-requests",
        ]);

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

