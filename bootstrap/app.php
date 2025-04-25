<?php

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ValidationException $e, $request) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        });

        $exceptions->renderable(function (QueryException $e, $request) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'message' => 'Database constraint violation',
                    'error' => $e->getMessage(),
                ], 400);
            }

            return null;
        });

        $exceptions->renderable(function (Throwable $e, $request) {
            if ($e instanceof HttpExceptionInterface) {
                return response()->json([
                    'message' => $e->getMessage() ?: 'Server error',
                ], $e->getStatusCode());
            }

            return response()->json([
                'message' => 'Unexpected error',
                'exception' => get_class($e),
                'trace' => config('app.debug') ? $e->getTrace() : [],
            ], 500);
        });
    })->create();
