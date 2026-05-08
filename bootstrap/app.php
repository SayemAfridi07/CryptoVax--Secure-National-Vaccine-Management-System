<?php

use App\Http\Middleware\NotRegistered;
use App\Http\Middleware\Registered;
use App\Http\Middleware\Role;
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
        
        
        $middleware->web(append: [
            \App\Http\Middleware\SecureSession::class,
        ]);

        
        $middleware->alias([
            'not-registered' => NotRegistered::class,
            'registered'     => Registered::class,
            'role'           => Role::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();