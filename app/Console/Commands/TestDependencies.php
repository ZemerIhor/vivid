<?php

namespace App\Console\Commands;

use App\Services\ReviewService;
use App\Services\LanguageService;
use App\Repositories\BlogPostRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\ReviewRepositoryInterface;
use Illuminate\Console\Command;

class TestDependencies extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:test-dependencies';

    /**
     * The console command description.
     */
    protected $description = 'Test that all dependencies can be resolved correctly';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🧪 Testing dependency injection...');

        $tests = [
            'ReviewService' => ReviewService::class,
            'LanguageService' => LanguageService::class,
            'BlogPostRepository' => BlogPostRepositoryInterface::class,
            'ProductRepository' => ProductRepositoryInterface::class,
            'ReviewRepository' => ReviewRepositoryInterface::class,
        ];

        $passed = 0;
        $failed = 0;

        foreach ($tests as $name => $class) {
            try {
                $instance = app($class);
                $this->line("✅ {$name}: " . get_class($instance));
                $passed++;
            } catch (\Exception $e) {
                $this->error("❌ {$name}: " . $e->getMessage());
                $failed++;
            }
        }

        $this->info("\n📊 Results:");
        $this->info("✅ Passed: {$passed}");
        if ($failed > 0) {
            $this->error("❌ Failed: {$failed}");
        }

        // Test Livewire components
        $this->info("\n🎛️ Testing Livewire components...");
        
        $livewireComponents = [
            'Home' => \App\Livewire\Home::class,
            'ProductPage' => \App\Livewire\ProductPage::class,
            'SubmitReview' => \App\Livewire\SubmitReview::class,
            'ReviewsPage' => \App\Livewire\ReviewsPage::class,
            'BlogPage' => \App\Livewire\BlogPage::class,
            'BlogPostPage' => \App\Livewire\BlogPostPage::class,
        ];

        $livewirePassed = 0;
        $livewireFailed = 0;

        foreach ($livewireComponents as $name => $class) {
            try {
                // Test that component can be instantiated
                $instance = new $class();
                $this->line("✅ {$name}: Component created successfully");
                $livewirePassed++;
            } catch (\Exception $e) {
                $this->error("❌ {$name}: " . $e->getMessage());
                $livewireFailed++;
            }
        }

        $this->info("\n📊 Livewire Results:");
        $this->info("✅ Passed: {$livewirePassed}");
        if ($livewireFailed > 0) {
            $this->error("❌ Failed: {$livewireFailed}");
        }

        $totalFailed = $failed + $livewireFailed;
        
        if ($totalFailed === 0) {
            $this->info("\n🎉 All dependencies are working correctly!");
            return Command::SUCCESS;
        } else {
            $this->error("\n💥 Some dependencies failed. Please check the errors above.");
            return Command::FAILURE;
        }
    }
}
