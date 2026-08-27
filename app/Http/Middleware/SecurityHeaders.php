<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     * 
     * ✅ SECURITY FIX: Add comprehensive HTTP security headers
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ✅ Remove server information leakage
        $response->headers->remove('X-Powered-By');
        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
        }
        
        // ✅ Prevent XSS attacks
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // ✅ Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // ✅ Enable browser XSS protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // ✅ Force HTTPS in production
        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        
        // ✅ Content Security Policy (relaxed for Livewire & Tailwind)
        $csp = implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "form-action 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com",
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://fonts.googleapis.com https://cdn.jsdelivr.net https://unpkg.com",
            "font-src 'self' https://fonts.bunny.net https://fonts.gstatic.com data:",
            "img-src 'self' data: https: blob:",
            "connect-src 'self' ws: wss:",
            "frame-ancestors 'self'",
        ]);
        
        // Only apply CSP in production to avoid breaking development
        if (config('app.env') === 'production') {
            $response->headers->set('Content-Security-Policy', $csp);
        }
        
        // ✅ Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // ✅ Permissions Policy (formerly Feature-Policy)
        $permissions = implode(', ', [
            'geolocation=()',
            'microphone=()',
            'camera=()',
            'payment=()',
        ]);
        $response->headers->set('Permissions-Policy', $permissions);

        return $response;
    }
}