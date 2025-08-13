<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CleanLogs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:clean-logs 
                           {--days=30 : Remove log files older than this many days}
                           {--size=100 : Remove log files larger than this many MB}
                           {--dry-run : Show what would be deleted without actually deleting}
                           {--force : Force deletion without confirmation}';

    /**
     * The console command description.
     */
    protected $description = 'Clean old and large log files';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $maxSizeMB = (int) $this->option('size');
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        $this->info("🧹 Cleaning log files...");
        $this->info("Parameters:");
        $this->line("  - Older than: {$days} days");
        $this->line("  - Larger than: {$maxSizeMB} MB");
        $this->line("  - Dry run: " . ($dryRun ? 'Yes' : 'No'));

        try {
            $logPath = storage_path('logs');
            
            if (!File::exists($logPath)) {
                $this->error("Log directory not found: {$logPath}");
                return Command::FAILURE;
            }

            $files = File::files($logPath);
            $filesToDelete = [];
            $totalSize = 0;

            foreach ($files as $file) {
                $shouldDelete = false;
                $reason = [];

                // Check age
                $fileAge = now()->diffInDays(File::lastModified($file->getPathname()));
                if ($fileAge > $days) {
                    $shouldDelete = true;
                    $reason[] = "age: {$fileAge} days";
                }

                // Check size
                $fileSizeMB = File::size($file->getPathname()) / 1024 / 1024;
                if ($fileSizeMB > $maxSizeMB) {
                    $shouldDelete = true;
                    $reason[] = "size: " . round($fileSizeMB, 2) . " MB";
                }

                if ($shouldDelete) {
                    $filesToDelete[] = [
                        'path' => $file->getPathname(),
                        'name' => $file->getFilename(),
                        'size' => $fileSizeMB,
                        'age' => $fileAge,
                        'reason' => implode(', ', $reason),
                    ];
                    $totalSize += $fileSizeMB;
                }
            }

            if (empty($filesToDelete)) {
                $this->info("✅ No log files need cleaning based on the specified criteria.");
                return Command::SUCCESS;
            }

            $this->info("\n📁 Files to be " . ($dryRun ? 'deleted (DRY RUN)' : 'deleted') . ":");
            
            $headers = ['File', 'Size (MB)', 'Age (days)', 'Reason'];
            $rows = [];

            foreach ($filesToDelete as $file) {
                $rows[] = [
                    $file['name'],
                    round($file['size'], 2),
                    $file['age'],
                    $file['reason'],
                ];
            }

            $this->table($headers, $rows);
            $this->info("Total files: " . count($filesToDelete));
            $this->info("Total size: " . round($totalSize, 2) . " MB");

            if ($dryRun) {
                $this->info("\n🔍 This was a dry run. No files were actually deleted.");
                return Command::SUCCESS;
            }

            if (!$force && !$this->confirm("\nProceed with deletion?")) {
                $this->info("Cleanup cancelled.");
                return Command::SUCCESS;
            }

            // Perform deletion
            $deleted = 0;
            $deletedSize = 0;
            $errors = [];

            foreach ($filesToDelete as $file) {
                try {
                    if (File::delete($file['path'])) {
                        $deleted++;
                        $deletedSize += $file['size'];
                        $this->line("✅ Deleted: {$file['name']}");
                    } else {
                        $errors[] = "Failed to delete: {$file['name']}";
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error deleting {$file['name']}: " . $e->getMessage();
                }
            }

            $this->info("\n📊 Cleanup Summary:");
            $this->info("Files deleted: {$deleted}/" . count($filesToDelete));
            $this->info("Space freed: " . round($deletedSize, 2) . " MB");

            if (!empty($errors)) {
                $this->warn("\n⚠️  Errors encountered:");
                foreach ($errors as $error) {
                    $this->line("  - {$error}");
                }
            }

            // Additional cleanup for specific Laravel log patterns
            $this->cleanSpecificLogPatterns($logPath, $days, $dryRun);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Failed to clean logs: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Clean specific Laravel log patterns
     */
    private function cleanSpecificLogPatterns(string $logPath, int $days, bool $dryRun): void
    {
        $patterns = [
            'laravel-*.log',
            'worker-*.log',
            'horizon-*.log',
        ];

        $cleaned = 0;

        foreach ($patterns as $pattern) {
            $files = glob("{$logPath}/{$pattern}");
            
            foreach ($files as $file) {
                $fileAge = now()->diffInDays(filemtime($file));
                
                if ($fileAge > $days) {
                    if (!$dryRun) {
                        if (unlink($file)) {
                            $cleaned++;
                            $this->line("✅ Cleaned pattern file: " . basename($file));
                        }
                    } else {
                        $this->line("🔍 Would clean: " . basename($file));
                    }
                }
            }
        }

        if ($cleaned > 0) {
            $this->info("📁 Cleaned {$cleaned} additional pattern-based log files.");
        }
    }
}
