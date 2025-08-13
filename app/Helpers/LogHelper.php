<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    /**
     * Логирование действий пользователя
     */
    public static function userAction(string $action, array $context = []): void
    {
        $context = array_merge([
            'user_id' => Auth::id(),
            'user_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
            'method' => request()->method(),
            'timestamp' => now()->toISOString(),
        ], $context);

        Log::info("User action: {$action}", $context);
    }

    /**
     * Логирование ошибок с контекстом
     */
    public static function error(string $message, \Throwable $exception, array $context = []): void
    {
        $context = array_merge([
            'exception_class' => get_class($exception),
            'exception_message' => $exception->getMessage(),
            'exception_file' => $exception->getFile(),
            'exception_line' => $exception->getLine(),
            'user_id' => Auth::id(),
            'user_ip' => request()->ip(),
            'url' => request()->url(),
            'timestamp' => now()->toISOString(),
        ], $context);

        Log::error($message, $context);
    }

    /**
     * Логирование производительности
     */
    public static function performance(string $operation, float $duration, array $context = []): void
    {
        $context = array_merge([
            'duration_ms' => round($duration * 1000, 2),
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'timestamp' => now()->toISOString(),
        ], $context);

        $level = $duration > 1.0 ? 'warning' : 'info';
        Log::$level("Performance: {$operation}", $context);
    }

    /**
     * Логирование безопасности
     */
    public static function security(string $event, array $context = []): void
    {
        $context = array_merge([
            'user_id' => Auth::id(),
            'user_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
            'session_id' => session()->getId(),
            'timestamp' => now()->toISOString(),
        ], $context);

        Log::warning("Security event: {$event}", $context);
    }

    /**
     * Логирование API запросов
     */
    public static function apiRequest(string $endpoint, array $data = [], ?float $duration = null): void
    {
        $context = [
            'endpoint' => $endpoint,
            'method' => request()->method(),
            'user_id' => Auth::id(),
            'user_ip' => request()->ip(),
            'data_size' => strlen(json_encode($data)),
            'timestamp' => now()->toISOString(),
        ];

        if ($duration !== null) {
            $context['duration_ms'] = round($duration * 1000, 2);
        }

        if (!empty($data)) {
            $context['data'] = $data;
        }

        Log::info("API request: {$endpoint}", $context);
    }

    /**
     * Логирование изменений в базе данных
     */
    public static function modelChange(string $model, string $action, int|string $id, array $changes = []): void
    {
        $context = [
            'model' => $model,
            'action' => $action,
            'model_id' => $id,
            'user_id' => Auth::id(),
            'user_ip' => request()->ip(),
            'timestamp' => now()->toISOString(),
        ];

        if (!empty($changes)) {
            $context['changes'] = $changes;
        }

        Log::info("Model {$action}: {$model}#{$id}", $context);
    }

    /**
     * Логирование медленных запросов
     */
    public static function slowQuery(string $sql, array $bindings, float $duration): void
    {
        $context = [
            'sql' => $sql,
            'bindings' => $bindings,
            'duration_ms' => round($duration, 2),
            'user_id' => Auth::id(),
            'url' => request()->url(),
            'timestamp' => now()->toISOString(),
        ];

        Log::warning('Slow query detected', $context);
    }

    /**
     * Логирование кэш операций
     */
    public static function cache(string $operation, string $key, bool $hit = null, ?float $duration = null): void
    {
        $context = [
            'operation' => $operation,
            'key' => $key,
            'timestamp' => now()->toISOString(),
        ];

        if ($hit !== null) {
            $context['hit'] = $hit;
        }

        if ($duration !== null) {
            $context['duration_ms'] = round($duration * 1000, 2);
        }

        Log::debug("Cache {$operation}: {$key}", $context);
    }

    /**
     * Логирование файловых операций
     */
    public static function fileOperation(string $operation, string $path, array $context = []): void
    {
        $context = array_merge([
            'operation' => $operation,
            'path' => $path,
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString(),
        ], $context);

        Log::info("File {$operation}: {$path}", $context);
    }

    /**
     * Логирование email отправки
     */
    public static function email(string $event, string $to, string $subject, array $context = []): void
    {
        $context = array_merge([
            'event' => $event,
            'to' => $to,
            'subject' => $subject,
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString(),
        ], $context);

        Log::info("Email {$event}: {$subject}", $context);
    }

    /**
     * Structured логирование для аналитики
     */
    public static function analytics(string $event, array $properties = []): void
    {
        $context = [
            'event' => $event,
            'properties' => $properties,
            'user_id' => Auth::id(),
            'session_id' => session()->getId(),
            'user_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->url(),
            'referrer' => request()->header('referer'),
            'timestamp' => now()->toISOString(),
        ];

        Log::channel('analytics')->info($event, $context);
    }

    /**
     * Логирование с автоматическим определением уровня
     */
    public static function auto(string $message, array $context = [], ?\Throwable $exception = null): void
    {
        if ($exception) {
            self::error($message, $exception, $context);
            return;
        }

        // Определяем уровень по ключевым словам
        $message_lower = strtolower($message);
        
        if (str_contains($message_lower, 'error') || str_contains($message_lower, 'fail')) {
            Log::error($message, $context);
        } elseif (str_contains($message_lower, 'warning') || str_contains($message_lower, 'slow')) {
            Log::warning($message, $context);
        } elseif (str_contains($message_lower, 'debug')) {
            Log::debug($message, $context);
        } else {
            Log::info($message, $context);
        }
    }

    /**
     * Создать контекст для логирования из request
     */
    public static function requestContext(): array
    {
        return [
            'method' => request()->method(),
            'url' => request()->url(),
            'user_id' => Auth::id(),
            'user_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'timestamp' => now()->toISOString(),
        ];
    }
}
