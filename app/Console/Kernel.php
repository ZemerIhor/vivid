<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Очистка старых логов каждую неделю
        $schedule->command('app:clean-logs --days=30 --size=100 --force')
                 ->weekly()
                 ->sundays()
                 ->at('02:00')
                 ->emailOutputOnFailure('admin@example.com');

        // Очистка старых неопубликованных отзывов каждый месяц
        $schedule->command('app:reviews clean --days=60 --force')
                 ->monthly()
                 ->at('03:00');

        // Диагностика системы каждый день
        $schedule->command('app:diagnostics --json')
                 ->daily()
                 ->at('06:00')
                 ->emailOutputOnFailure('admin@example.com');

        // Очистка кэша каждую ночь (частичная)
        $schedule->command('app:clear-cache products --force')
                 ->daily()
                 ->at('04:00');

        // Очистка кэша сессий и временных файлов
        $schedule->command('cache:clear')
                 ->weekly()
                 ->saturdays()
                 ->at('01:00');
                 
        $schedule->command('session:gc')
                 ->daily()
                 ->at('05:00');

        // Оптимизация базы данных
        $schedule->command('model:prune')
                 ->daily()
                 ->at('01:30');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
