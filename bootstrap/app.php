<?php

use App\Http\Middleware\Role;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
         api: __DIR__.'/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register your route middleware here
        $middleware->alias([
            'employee' => \App\Http\Middleware\EmployeeMiddleware::class,
            'role' => \App\Http\Middleware\Role::class,  
            'user.status' => \App\Http\Middleware\CheckUserStatus::class,
            'check.status' => \App\Http\Middleware\CheckUserStatus::class,

              $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
        ])
        
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
