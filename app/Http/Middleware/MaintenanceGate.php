<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceGate
{
    public function handle(Request $request, Closure $next): Response
    {
        // Wajib HTTPS di production (mengurangi risiko token dicuri)
        if (app()->isProduction() && !$request->isSecure()) {
            abort(403);
        }

        $expected = (string) env('MAINTENANCE_TOKEN', '');
        if ($expected === '') {
            // Fail closed: jangan pernah membuka endpoint maintenance tanpa token
            abort(404);
        }

        // Ambil token dari header atau body (POST form)
        $token = (string) ($request->header('X-Maintenance-Token') ?? $request->input('token', ''));
        if ($token === '' || !hash_equals($expected, $token)) {
            abort(404);
        }

        return $next($request);
    }
}

