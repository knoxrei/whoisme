<?php

use App\Http\Middleware\HandleAnonymousUser;
use App\Http\Middleware\HandleRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\CheckRoleExpiration::class);
        $middleware->web(append: [
            HandleAnonymousUser::class,
            \App\Http\Middleware\UpdateLastActive::class,
        ]);
        $middleware->alias([
            'role' => HandleRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
