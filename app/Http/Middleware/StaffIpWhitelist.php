<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class StaffIpWhitelist
{
    /**
     * Handle an incoming request.
     *
     * ✅ SECURITY FIX: IP Whitelist untuk staff login
     * Hanya IP yang terdaftar yang dapat mengakses staff login
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Development: allow all IPs
        if (app()->environment('local')) {
            return $next($request);
        }

        // Daftar IP yang diizinkan (whitelist)
        // IMPORTANT: Ganti dengan IP address warung Anda
        $allowedIps = config('security.staff_ip_whitelist', [
            '127.0.0.1',           // Localhost untuk development
            '::1',                  // IPv6 localhost
            // Tambahkan IP warung Anda di sini
            // '192.168.1.100',     // Contoh: IP komputer kasir
            // '192.168.1.101',     // Contoh: IP komputer manager
        ]);

        $clientIp = $request->ip();

        // Check if IP is whitelisted
        if (!in_array($clientIp, $allowedIps)) {
            // Log unauthorized access attempt
            Log::warning('Unauthorized staff login attempt', [
                'ip' => $clientIp,
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'timestamp' => now()
            ]);

            // Return 404 instead of 403 to hide endpoint existence
            abort(404);
        }

        return $next($request);
    }
}