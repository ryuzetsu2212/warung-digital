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
            // ⚠ JANGAN hapus 'unsafe-eval' — Livewire/Alpine.js memakai new Function()
            // untuk evaluasi wire: expression. Dihapus → login submit diam-diam mati.
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: blob: https://images.unsplash.com https://plus.unsplash.com https://pbaqettpfiqpxsccuiox.supabase.co",
            "connect-src 'self'",
            "frame-ancestors 'self'",
            "form-action 'self'",
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

        // ✅ Fix Big Redirect: clear response body for redirects
        if ($response->isRedirect()) {
            $response->setContent(null);
        }

        return $response;
    }
}