<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware; // 👈 Import your middleware
use App\Http\Middleware\RedirectMiddleware;
use Illuminate\Support\Facades\Redirect;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register your middleware alias here
       $middleware->alias([
            'role' => RoleMiddleware::class,
            'redirect.auth'=>RedirectMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
