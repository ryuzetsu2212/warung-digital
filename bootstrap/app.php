<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ✅ Register middleware aliases
        $middleware->alias([
            'staff.auth' => \App\Http\Middleware\StaffAuthenticated::class,
            'admin.auth' => \App\Http\Middleware\AdminAuthenticated::class,
            'throttle.staff' => \App\Http\Middleware\ThrottleStaffLogin::class,
            'staff.ip.whitelist' => \App\Http\Middleware\StaffIpWhitelist::class,
        ]);

        // ✅ Apply security headers globally
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        $middleware->validateCsrfTokens(except: [
            //
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();