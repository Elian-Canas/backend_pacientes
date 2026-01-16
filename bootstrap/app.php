<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api/',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
        $middleware->alias([
            'jwt.auth' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Manejar UnauthorizedHttpException (token no proporcionado)
        $exceptions->render(function (UnauthorizedHttpException $e) {
            return response()->json(['error' => 'token_not_found'], 400);
        });

        // Opcional: otros manejadores JWT
        $exceptions->render(function (TokenExpiredException $e) {
            return response()->json(['error' => 'token_expired'], 401);
        });

        $exceptions->render(function (TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid'], 401);
        });
    })->create();
