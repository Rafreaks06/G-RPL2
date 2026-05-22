<?php

use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(
    basePath: dirname(__DIR__)
)

    ->withRouting(
        web: __DIR__.'/../routes/web.php',

        api: __DIR__.'/../routes/api.php',

        commands: __DIR__.'/../routes/console.php',

        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (
            AuthenticationException $e,
            $request
        ) {

            return response()->json([
                'success' => false,

                'message' => 'Unauthenticated'
            ], Response::HTTP_UNAUTHORIZED);
        });

        //Forbidden Access Message
        $exceptions->render(function (
            UnauthorizedException $e,
            $request
        ) {

            return response()->json([
                'success' => false,

                'message' => 'Access Forbidden'
            ], Response::HTTP_FORBIDDEN);
        });
    })

    ->create();