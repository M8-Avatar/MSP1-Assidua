<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 'unsafe-inline' requis pour Bootstrap 5 (styles dynamiques) et le HMR Vite en dev.
        // En production : supprimer 'unsafe-inline', utiliser des nonces CSP générés côté serveur
        // (ex. vite-plugin-csp ou middleware nonce Blade) et retirer les ports Vite de connect-src.
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "img-src 'self' data:; " .
            "connect-src 'self' ws://localhost:5173 http://localhost:5173"
        );

        return $response;
    }
}