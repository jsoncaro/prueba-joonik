<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        //
    }

    public function render($request, Throwable $exception): Response
    {
        // Forzar siempre JSON en las rutas API
        if ($request->is('api/*')) {
            $status = $this->isHttpException($exception)
                ? $exception->getCode()
                : 500;

            return response()->json([
                'error' => [
                    'message' => $exception->getMessage(),
                    'code'    => $exception->getCode() ?: 0
                ]
            ], $status);
        }

        return parent::render($request, $exception);
    }
}
