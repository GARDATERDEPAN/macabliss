<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $response = $next($request);

        // Mencegah website di-embed oleh domain lain
        $response->headers->set(
            'X-Frame-Options',
            'SAMEORIGIN'
        );

        // Mencegah MIME type sniffing
        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );

        // Mengatur informasi referrer
        $response->headers->set(
            'Referrer-Policy',
            'strict-origin-when-cross-origin'
        );

        // Izinkan geolocation untuk domain sendiri
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(self)'
        );

        if (app()->environment('production')) {

            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://unpkg.com; " .
                "style-src 'self' 'unsafe-inline' https://fonts.bunny.net; " .
                "font-src 'self' https://fonts.bunny.net data:; " .
                "img-src 'self' data: blob: https:; " .
                "connect-src 'self' https://nominatim.openstreetmap.org https://*.tile.openstreetmap.org; " .
                "frame-ancestors 'self'; " .
                "object-src 'none'; " .
                "base-uri 'self'; " .
                "form-action 'self';"
            );

        }

        if ($request->secure()) {

            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );

        }

        return $response;
    }
}