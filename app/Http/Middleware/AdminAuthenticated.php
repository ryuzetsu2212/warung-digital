<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class AdminAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in as admin
        if (Session::get('staff_logged_in') !== true || Session::get('staff_role') !== 'admin') {
            return redirect()->route('staff.login');
        }

        // Session fingerprint validation
        $currentFingerprint = $this->generateFingerprint($request);
        $storedFingerprint = Session::get('staff_session_fingerprint');

        if (!$storedFingerprint || $currentFingerprint !== $storedFingerprint) {
            Session::flush();
            return redirect()->route('staff.login')
                ->with('error', 'Sesi admin tidak valid. Silakan login kembali.');
        }

        // Additional admin session timeout (shorter than staff: 30 minutes)
        $loginTime = Session::get('staff_login_time');
        if ($loginTime && now()->diffInMinutes($loginTime) > 30) {
            Session::flush();
            return redirect()->route('staff.login')
                ->with('error', 'Sesi admin telah berakhir. Silakan login kembali.');
        }

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
            $request->header('Accept-Language') ?? 'unknown'
        ]));
    }
}
