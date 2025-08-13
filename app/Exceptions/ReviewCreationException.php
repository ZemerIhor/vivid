<?php

namespace App\Exceptions;

use Exception;

class ReviewCreationException extends Exception
{
    public function __construct(string $message = 'Failed to create review', int $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        \Log::error('Review creation failed', [
            'message' => $this->getMessage(),
            'url' => request()->url(),
            'user_agent' => request()->userAgent(),
            'ip' => request()->ip(),
            'previous' => $this->getPrevious()?->getMessage(),
        ]);
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Review creation failed',
                'message' => 'Unable to submit your review at this time. Please try again later.',
            ], 500);
        }

        return back()->with('error', 'Произошла ошибка при отправке отзыва. Пожалуйста, попробуйте еще раз.');
    }
}
