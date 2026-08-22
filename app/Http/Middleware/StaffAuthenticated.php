<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class StaffAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check basic authentication
        if (!Session::get('staff_logged_in')) {
            return redirect()->route('staff.login');
        }

        // Session fixation prevention: Validate session fingerprint
        $currentFingerprint = $this->generateFingerprint($request);
        $storedFingerprint = Session::get('staff_session_fingerprint');

        if (!$storedFingerprint || $currentFingerprint !== $storedFingerprint) {
            // Session suspicious - force re-authentication
            Session::flush();
            return redirect()->route('staff.login')
                ->with('error', 'Sesi Anda tidak valid. Silakan login kembali.');
        }

        // Session timeout check (additional security)
        $loginTime = Session::get('staff_login_time');
        if ($loginTime && now()->diffInMinutes($loginTime) > config('session.lifetime')) {
            Session::flush();
            return redirect()->route('staff.login')
                ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        // Update last activity
        Session::put('staff_last_activity', now());

        return $next($request);
    }

    /**
     * Generate session fingerprint based on request characteristics
     */
    private function generateFingerprint(Request $request): string
    {
        return hash('sha256', implode('|', [
            $request->userAgent() ?? 'unknown',
            $request->ip(),
            $request->header('Accept-Language') ?? 'unknown'
        ]));
    }
}
