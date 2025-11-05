<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        ProductNotFoundException::class,
        BlogPostNotFoundException::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Логируем со сконтекстом
            if (app()->bound('sentry') && $this->shouldReport($e)) {
                app('sentry')->captureException($e);
            }
        });

        $this->renderable(function (ProductNotFoundException $e, Request $request) {
            return $e->render($request);
        });

        $this->renderable(function (BlogPostNotFoundException $e, Request $request) {
            return $e->render($request);
        });

        $this->renderable(function (ReviewCreationException $e, Request $request) {
            return $e->render($request);
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Обработка 404 ошибок
        if ($e instanceof NotFoundHttpException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Resource not found',
                    'message' => 'The requested resource was not found.',
                ], 404);
            }

            // Проверяем существование представления
            if (view()->exists('errors.404')) {
                return response()->view('errors.404', [], 404);
            }
            
            // Fallback на простой текст, если представления нет
            return response('Page not found', 404);
        }

        // Обработка ошибок валидации
        if ($e instanceof ValidationException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }
        }

        // Обработка 500 ошибок
        if ($e instanceof \Error || $e instanceof \Exception) {
            if (config('app.debug')) {
                return parent::render($request, $e);
            }

            // Логируем ошибку
            Log::error('Unhandled exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => $request->url(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Internal server error',
                    'message' => 'Something went wrong. Please try again later.',
                ], 500);
            }

            // Проверяем существование представления
            if (view()->exists('errors.500')) {
                return response()->view('errors.500', [], 500);
            }
            
            // Fallback на простой текст, если представления нет
            return response('Internal server error', 500);
        }

        return parent::render($request, $e);
    }

    /**
     * Convert a validation exception into a JSON response.
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'error' => 'Validation failed',
            'message' => 'The given data was invalid.',
            'errors' => $exception->errors(),
        ], $exception->status);
    }

    /**
     * Convert an authentication exception into a response.
     */
    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'You are not authenticated.',
            ], 401);
        }

        return redirect()->guest(route('filament.lunar.auth.login'));
    }
}
