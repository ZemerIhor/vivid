<?php

namespace App\Console\Commands;

use App\Helpers\CacheHelper;
use App\Models\BlogPost;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SystemDiagnostics extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:diagnostics 
                           {--detailed : Show detailed information}
                           {--json : Output in JSON format}';

    /**
     * The console command description.
     */
    protected $description = 'Run system diagnostics and health checks';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $detailed = $this->option('detailed');
        $json = $this->option('json');

        $diagnostics = $this->runDiagnostics($detailed);

        if ($json) {
            $this->line(json_encode($diagnostics, JSON_PRETTY_PRINT));
        } else {
            $this->displayDiagnostics($diagnostics, $detailed);
        }

        // Return appropriate exit code
        $hasErrors = collect($diagnostics)->contains(function ($item) {
            return isset($item['status']) && $item['status'] === 'error';
        });

        return $hasErrors ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * Run all diagnostic checks
     */
    private function runDiagnostics(bool $detailed): array
    {
        $diagnostics = [
            'timestamp' => now()->toISOString(),
            'app' => $this->checkApplication(),
            'database' => $this->checkDatabase($detailed),
            'cache' => $this->checkCache($detailed),
            'storage' => $this->checkStorage($detailed),
            'models' => $this->checkModels($detailed),
            'performance' => $this->checkPerformance(),
        ];

        if ($detailed) {
            $diagnostics['environment'] = $this->checkEnvironment();
            $diagnostics['dependencies'] = $this->checkDependencies();
        }

        return $diagnostics;
    }

    /**
     * Check application health
     */
    private function checkApplication(): array
    {
        return [
            'name' => config('app.name'),
            'environment' => app()->environment(),
            'debug' => config('app.debug'),
            'url' => config('app.url'),
            'locale' => app()->getLocale(),
            'timezone' => config('app.timezone'),
            'version' => app()->version(),
            'status' => 'ok',
        ];
    }

    /**
     * Check database connectivity and health
     */
    private function checkDatabase(bool $detailed): array
    {
        try {
            $startTime = microtime(true);
            $connection = DB::connection();
            $connection->getPdo();
            $queryTime = microtime(true) - $startTime;

            $result = [
                'status' => 'ok',
                'driver' => $connection->getDriverName(),
                'connection_time_ms' => round($queryTime * 1000, 2),
            ];

            if ($detailed) {
                $result['database_name'] = $connection->getDatabaseName();
                $result['tables'] = $this->getTableStats();
            }

            return $result;

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check cache system
     */
    private function checkCache(bool $detailed): array
    {
        try {
            $testKey = 'diagnostics_test_' . time();
            $testValue = 'test_value';

            $startTime = microtime(true);
            $cached = CacheHelper::put($testKey, $testValue, 60);
            $writeTime = microtime(true) - $startTime;

            $startTime = microtime(true);
            $retrieved = CacheHelper::get($testKey);
            $readTime = microtime(true) - $startTime;

            CacheHelper::forget($testKey);

            $result = [
                'status' => $cached && $retrieved === $testValue ? 'ok' : 'warning',
                'driver' => config('cache.default'),
                'write_time_ms' => round($writeTime * 1000, 2),
                'read_time_ms' => round($readTime * 1000, 2),
            ];

            if ($detailed) {
                $result['info'] = CacheHelper::getInfo();
            }

            return $result;

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check storage systems
     */
    private function checkStorage(bool $detailed): array
    {
        $disks = ['local', 'public'];
        $results = [];

        foreach ($disks as $disk) {
            try {
                $testFile = 'diagnostics_test_' . time() . '.txt';
                $testContent = 'test content';

                $startTime = microtime(true);
                $written = Storage::disk($disk)->put($testFile, $testContent);
                $writeTime = microtime(true) - $startTime;

                $startTime = microtime(true);
                $content = Storage::disk($disk)->get($testFile);
                $readTime = microtime(true) - $startTime;

                Storage::disk($disk)->delete($testFile);

                $results[$disk] = [
                    'status' => $written && $content === $testContent ? 'ok' : 'warning',
                    'write_time_ms' => round($writeTime * 1000, 2),
                    'read_time_ms' => round($readTime * 1000, 2),
                ];

                if ($detailed) {
                    $results[$disk]['path'] = Storage::disk($disk)->path('');
                    $results[$disk]['available_space'] = $this->formatBytes(disk_free_space(Storage::disk($disk)->path('')));
                }

            } catch (\Exception $e) {
                $results[$disk] = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Check model data integrity
     */
    private function checkModels(bool $detailed): array
    {
        $models = [
            'reviews' => Review::class,
            'blog_posts' => BlogPost::class,
            'products' => Product::class,
        ];

        $results = [];

        foreach ($models as $name => $class) {
            try {
                $total = $class::count();
                
                $result = [
                    'total_count' => $total,
                    'status' => 'ok',
                ];

                if ($detailed && $name === 'reviews') {
                    $result['published'] = Review::where('published', true)->count();
                    $result['unpublished'] = Review::where('published', false)->count();
                    $result['average_rating'] = round(Review::where('published', true)->avg('rating') ?: 0, 2);
                }

                if ($detailed && $name === 'blog_posts') {
                    $result['published'] = BlogPost::where('published', true)->count();
                    $result['unpublished'] = BlogPost::where('published', false)->count();
                }

                $results[$name] = $result;

            } catch (\Exception $e) {
                $results[$name] = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Check system performance metrics
     */
    private function checkPerformance(): array
    {
        $startTime = microtime(true);
        $memoryStart = memory_get_usage(true);

        // Simple performance test
        for ($i = 0; $i < 10000; $i++) {
            $temp = md5($i);
        }

        $executionTime = microtime(true) - $startTime;
        $memoryUsed = memory_get_usage(true) - $memoryStart;

        return [
            'execution_time_ms' => round($executionTime * 1000, 2),
            'memory_used' => $this->formatBytes($memoryUsed),
            'memory_peak' => $this->formatBytes(memory_get_peak_usage(true)),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'status' => $executionTime < 0.1 ? 'ok' : 'warning',
        ];
    }

    /**
     * Check environment configuration
     */
    private function checkEnvironment(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'os' => PHP_OS,
            'extensions' => [
                'pdo' => extension_loaded('pdo'),
                'mbstring' => extension_loaded('mbstring'),
                'openssl' => extension_loaded('openssl'),
                'redis' => extension_loaded('redis'),
                'gd' => extension_loaded('gd'),
                'curl' => extension_loaded('curl'),
            ],
        ];
    }

    /**
     * Check critical dependencies
     */
    private function checkDependencies(): array
    {
        $composerLock = base_path('composer.lock');
        
        if (!file_exists($composerLock)) {
            return ['status' => 'warning', 'message' => 'composer.lock not found'];
        }

        $lockData = json_decode(file_get_contents($composerLock), true);
        $packages = collect($lockData['packages'] ?? []);

        return [
            'total_packages' => $packages->count(),
            'laravel_framework' => $packages->firstWhere('name', 'laravel/framework')['version'] ?? 'not found',
            'livewire' => $packages->firstWhere('name', 'livewire/livewire')['version'] ?? 'not found',
            'status' => 'ok',
        ];
    }

    /**
     * Get database table statistics
     */
    private function getTableStats(): array
    {
        try {
            $tables = DB::select("SHOW TABLES");
            $stats = [];

            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];
                $count = DB::table($tableName)->count();
                $stats[$tableName] = $count;
            }

            return $stats;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Display diagnostics in human-readable format
     */
    private function displayDiagnostics(array $diagnostics, bool $detailed): void
    {
        $this->info('🔍 System Diagnostics Report');
        $this->info('==========================');
        $this->info('Generated: ' . $diagnostics['timestamp']);
        $this->line('');

        // Application
        $this->info('🚀 Application:');
        $app = $diagnostics['app'];
        $this->line("  Name: {$app['name']}");
        $this->line("  Environment: {$app['environment']}");
        $this->line("  Debug: " . ($app['debug'] ? 'Enabled' : 'Disabled'));
        $this->line("  Version: {$app['version']}");
        $this->line('');

        // Database
        $this->info('🗄️  Database:');
        $db = $diagnostics['database'];
        if ($db['status'] === 'ok') {
            $this->line("  Status: ✅ Connected");
            $this->line("  Driver: {$db['driver']}");
            $this->line("  Connection Time: {$db['connection_time_ms']}ms");
        } else {
            $this->line("  Status: ❌ Error - {$db['message']}");
        }
        $this->line('');

        // Cache
        $this->info('💾 Cache:');
        $cache = $diagnostics['cache'];
        if ($cache['status'] === 'ok') {
            $this->line("  Status: ✅ Working");
            $this->line("  Driver: {$cache['driver']}");
            $this->line("  Read/Write: {$cache['read_time_ms']}ms / {$cache['write_time_ms']}ms");
        } else {
            $this->line("  Status: ❌ Error - {$cache['message']}");
        }
        $this->line('');

        // Models
        $this->info('📊 Data Models:');
        foreach ($diagnostics['models'] as $name => $data) {
            if ($data['status'] === 'ok') {
                $this->line("  {$name}: ✅ {$data['total_count']} records");
            } else {
                $this->line("  {$name}: ❌ Error - {$data['message']}");
            }
        }
        $this->line('');

        // Performance
        $this->info('⚡ Performance:');
        $perf = $diagnostics['performance'];
        $status = $perf['status'] === 'ok' ? '✅' : '⚠️';
        $this->line("  Status: {$status}");
        $this->line("  Memory Peak: {$perf['memory_peak']}");
        $this->line("  Memory Limit: {$perf['memory_limit']}");
        $this->line('');

        if ($detailed) {
            $this->info('🔧 Environment Details:');
            $env = $diagnostics['environment'];
            $this->line("  PHP: {$env['php_version']}");
            $this->line("  OS: {$env['os']}");
            $this->line("  Extensions: " . implode(', ', array_keys(array_filter($env['extensions']))));
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
