<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Http\Middleware\ForceJsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('api', ForceJsonResponse::class);
        $middleware->appendToGroup('api', ApiKeyMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function ($request, Throwable $e) {
            return $request->is('api/*') || $request->expectsJson();
        });

        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*')) {

                // Manejar validaciones con 422
                if ($e instanceof ValidationException) {
                    return response()->json([
                        'error' => [
                            'message' => $e->getMessage(),
                            'code'    => 422,
                            'errors'  => $e->errors()
                        ]
                    ], 422);
                }

                // Manejar excepciones HTTP
                if ($e instanceof HttpExceptionInterface) {
                    $status = $e->getStatusCode();
                } else {
                    $status = 500;
                }

                return response()->json([
                    'error' => [
                        'message' => $e->getMessage(),
                        'code'    => $e->getCode() ?: 0
                    ]
                ], $status);
            }
        });
    })->create();
