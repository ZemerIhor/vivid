<?php

namespace App\Exceptions;

use Exception;

class ProductNotFoundException extends Exception
{
    public function __construct(string $slug, int $code = 404, Exception $previous = null)
    {
        $message = "Product with slug '{$slug}' not found";
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        \Log::warning('Product not found', [
            'slug' => $this->extractSlugFromMessage(),
            'url' => request()->url(),
            'user_agent' => request()->userAgent(),
            'ip' => request()->ip(),
        ]);
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Product not found',
                'message' => $this->getMessage(),
            ], 404);
        }

        return response()->view('errors.product-not-found', [
            'message' => $this->getMessage(),
        ], 404);
    }

    private function extractSlugFromMessage(): string
    {
        preg_match("/with slug '(.*)' not found/", $this->getMessage(), $matches);
        return $matches[1] ?? 'unknown';
    }
}
