<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheHelper
{
    /**
     * Время кэширования по умолчанию (в секундах)
     */
    private const DEFAULT_TTL = 3600; // 1 час

    /**
     * Время кэширования для разных типов данных
     */
    private const TTL_MAPPING = [
        'products' => 1800,      // 30 минут
        'blog_posts' => 3600,    // 1 час
        'reviews' => 3600,       // 1 час
        'collections' => 7200,   // 2 часа
        'static_content' => 86400, // 24 часа
    ];

    /**
     * Получить данные из кэша или выполнить callback
     */
    public static function remember(string $key, \Closure $callback, ?int $ttl = null, ?string $type = null): mixed
    {
        $ttl = $ttl ?? self::getTtl($type);
        
        try {
            return Cache::remember($key, $ttl, $callback);
        } catch (\Exception $e) {
            Log::error('Cache remember failed', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            
            // Fallback: выполняем callback без кэширования
            return $callback();
        }
    }

    /**
     * Получить данные из кэша
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            return Cache::get($key, $default);
        } catch (\Exception $e) {
            Log::error('Cache get failed', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            
            return $default;
        }
    }

    /**
     * Сохранить данные в кэш
     */
    public static function put(string $key, mixed $value, ?int $ttl = null, ?string $type = null): bool
    {
        $ttl = $ttl ?? self::getTtl($type);
        
        try {
            return Cache::put($key, $value, $ttl);
        } catch (\Exception $e) {
            Log::error('Cache put failed', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Удалить данные из кэша
     */
    public static function forget(string $key): bool
    {
        try {
            return Cache::forget($key);
        } catch (\Exception $e) {
            Log::error('Cache forget failed', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Удалить множественные ключи
     */
    public static function forgetMany(array $keys): int
    {
        $deleted = 0;
        
        foreach ($keys as $key) {
            if (self::forget($key)) {
                $deleted++;
            }
        }
        
        return $deleted;
    }

    /**
     * Очистить кэш по паттерну (для Redis)
     */
    public static function forgetByPattern(string $pattern): int
    {
        try {
            if (config('cache.default') === 'redis') {
                $redis = Cache::getRedis();
                $keys = $redis->keys($pattern);
                
                if (empty($keys)) {
                    return 0;
                }
                
                return $redis->del($keys);
            }
            
            Log::warning('Pattern cache clearing not supported for current cache driver');
            return 0;
            
        } catch (\Exception $e) {
            Log::error('Cache pattern forget failed', [
                'pattern' => $pattern,
                'error' => $e->getMessage(),
            ]);
            
            return 0;
        }
    }

    /**
     * Очистить весь кэш
     */
    public static function flush(): bool
    {
        try {
            return Cache::flush();
        } catch (\Exception $e) {
            Log::error('Cache flush failed', [
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Проверить, есть ли ключ в кэше
     */
    public static function has(string $key): bool
    {
        try {
            return Cache::has($key);
        } catch (\Exception $e) {
            Log::error('Cache has failed', [
                'key' => $key,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Получить TTL для типа данных
     */
    private static function getTtl(?string $type = null): int
    {
        if ($type && isset(self::TTL_MAPPING[$type])) {
            return self::TTL_MAPPING[$type];
        }
        
        return self::DEFAULT_TTL;
    }

    /**
     * Создать ключ кэша с префиксом
     */
    public static function key(string $prefix, ...$parts): string
    {
        $key = $prefix;
        
        foreach ($parts as $part) {
            $key .= '.' . (is_array($part) ? md5(serialize($part)) : $part);
        }
        
        return $key;
    }

    /**
     * Создать ключ для модели
     */
    public static function modelKey(string $model, int|string $id, ?string $suffix = null): string
    {
        $key = strtolower(class_basename($model)) . '.' . $id;
        
        if ($suffix) {
            $key .= '.' . $suffix;
        }
        
        return $key;
    }

    /**
     * Создать ключ для коллекции
     */
    public static function collectionKey(string $model, array $filters = [], ?string $suffix = null): string
    {
        $key = strtolower(class_basename($model)) . '.collection';
        
        if (!empty($filters)) {
            $key .= '.' . md5(serialize($filters));
        }
        
        if ($suffix) {
            $key .= '.' . $suffix;
        }
        
        return $key;
    }

    /**
     * Получить информацию о кэше (для отладки)
     */
    public static function getInfo(): array
    {
        $info = [
            'driver' => config('cache.default'),
            'prefix' => config('cache.prefix'),
        ];
        
        try {
            if (config('cache.default') === 'redis') {
                $redis = Cache::getRedis();
                $info['redis_info'] = $redis->info('memory');
            }
        } catch (\Exception $e) {
            $info['error'] = $e->getMessage();
        }
        
        return $info;
    }
}
