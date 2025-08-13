<?php

namespace App\Console\Commands;

use App\Services\ReviewService;
use App\Models\Review;
use Illuminate\Console\Command;

class ManageReviews extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:reviews 
                           {action : Action to perform (list, publish, unpublish, stats, clean)}
                           {--id=* : Review IDs (for publish/unpublish actions)}
                           {--days=30 : Days threshold for clean action}
                           {--force : Force action without confirmation}
                           {--published : Show only published reviews}
                           {--unpublished : Show only unpublished reviews}';

    /**
     * The console command description.
     */
    protected $description = 'Manage reviews (list, publish, unpublish, clean old reviews)';

    /**
     * Execute the console command.
     */
    public function handle(ReviewService $reviewService): int
    {
        $action = $this->argument('action');

        try {
            return match ($action) {
                'list' => $this->listReviews($reviewService),
                'publish' => $this->publishReviews($reviewService),
                'unpublish' => $this->unpublishReviews($reviewService),
                'stats' => $this->showStats($reviewService),
                'clean' => $this->cleanOldReviews($reviewService),
                default => $this->showHelp(),
            };
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * List reviews
     */
    private function listReviews(ReviewService $reviewService): int
    {
        $query = Review::query();

        if ($this->option('published')) {
            $query->where('published', true);
        } elseif ($this->option('unpublished')) {
            $query->where('published', false);
        }

        $reviews = $query->orderBy('created_at', 'desc')->get();

        if ($reviews->isEmpty()) {
            $this->info('No reviews found.');
            return Command::SUCCESS;
        }

        $headers = ['ID', 'Name', 'Rating', 'Published', 'Created At'];
        $rows = [];

        foreach ($reviews as $review) {
            $rows[] = [
                $review->id,
                $review->name,
                str_repeat('⭐', $review->rating),
                $review->published ? '✅ Yes' : '❌ No',
                $review->created_at->format('Y-m-d H:i'),
            ];
        }

        $this->table($headers, $rows);
        $this->info("Total: {$reviews->count()} reviews");

        return Command::SUCCESS;
    }

    /**
     * Publish reviews
     */
    private function publishReviews(ReviewService $reviewService): int
    {
        $ids = $this->option('id');

        if (empty($ids)) {
            $this->error('No review IDs provided. Use --id option.');
            return Command::FAILURE;
        }

        if (!$this->option('force') && !$this->confirm("Publish " . count($ids) . " review(s)?")) {
            $this->info('Action cancelled.');
            return Command::SUCCESS;
        }

        $published = $reviewService->bulkPublishReviews($ids);

        $this->info("✅ Published {$published} out of " . count($ids) . " reviews.");
        return Command::SUCCESS;
    }

    /**
     * Unpublish reviews
     */
    private function unpublishReviews(ReviewService $reviewService): int
    {
        $ids = $this->option('id');

        if (empty($ids)) {
            $this->error('No review IDs provided. Use --id option.');
            return Command::FAILURE;
        }

        if (!$this->option('force') && !$this->confirm("Unpublish " . count($ids) . " review(s)?")) {
            $this->info('Action cancelled.');
            return Command::SUCCESS;
        }

        $unpublished = $reviewService->bulkUnpublishReviews($ids);

        $this->info("✅ Unpublished {$unpublished} out of " . count($ids) . " reviews.");
        return Command::SUCCESS;
    }

    /**
     * Show review statistics
     */
    private function showStats(ReviewService $reviewService): int
    {
        $stats = $reviewService->getRatingStatistics();
        $averageRating = $reviewService->getAverageRating();

        $this->info('📊 Review Statistics');
        $this->info('==================');
        $this->info("Average Rating: {$averageRating}/5");
        $this->info("Total Reviews: {$stats['total_reviews']}");
        $this->line('');

        $this->info('Rating Distribution:');
        for ($i = 5; $i >= 1; $i--) {
            $count = $stats['ratings'][$i];
            $percentage = $stats['total_reviews'] > 0 
                ? round(($count / $stats['total_reviews']) * 100, 1) 
                : 0;
            
            $bar = str_repeat('█', min(50, $count));
            $this->line("{$i}⭐ {$count} ({$percentage}%) {$bar}");
        }

        // Additional stats
        $totalReviews = Review::count();
        $publishedReviews = Review::where('published', true)->count();
        $unpublishedReviews = $totalReviews - $publishedReviews;

        $this->line('');
        $this->info('Publication Status:');
        $this->info("Published: {$publishedReviews}");
        $this->info("Unpublished: {$unpublishedReviews}");
        $this->info("Total: {$totalReviews}");

        return Command::SUCCESS;
    }

    /**
     * Clean old unpublished reviews
     */
    private function cleanOldReviews(ReviewService $reviewService): int
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $oldReviews = Review::where('published', false)
            ->where('created_at', '<', $cutoffDate)
            ->get();

        if ($oldReviews->isEmpty()) {
            $this->info("No unpublished reviews older than {$days} days found.");
            return Command::SUCCESS;
        }

        $this->info("Found {$oldReviews->count()} unpublished reviews older than {$days} days:");

        foreach ($oldReviews->take(10) as $review) {
            $this->line("- #{$review->id} by {$review->name} ({$review->created_at->diffForHumans()})");
        }

        if ($oldReviews->count() > 10) {
            $this->line("... and " . ($oldReviews->count() - 10) . " more");
        }

        if (!$this->option('force') && !$this->confirm("Delete these {$oldReviews->count()} old reviews?")) {
            $this->info('Cleanup cancelled.');
            return Command::SUCCESS;
        }

        $deleted = 0;
        foreach ($oldReviews as $review) {
            if ($reviewService->deleteReview($review->id)) {
                $deleted++;
            }
        }

        $this->info("✅ Cleaned up {$deleted} old reviews.");
        return Command::SUCCESS;
    }

    /**
     * Show help information
     */
    private function showHelp(): int
    {
        $this->info('Available actions:');
        $this->line('  list      - List reviews');
        $this->line('  publish   - Publish reviews by ID');
        $this->line('  unpublish - Unpublish reviews by ID');
        $this->line('  stats     - Show review statistics');
        $this->line('  clean     - Clean old unpublished reviews');
        $this->line('');
        $this->info('Examples:');
        $this->line('  php artisan app:reviews list --published');
        $this->line('  php artisan app:reviews publish --id=1 --id=2 --id=3');
        $this->line('  php artisan app:reviews stats');
        $this->line('  php artisan app:reviews clean --days=60 --force');

        return Command::SUCCESS;
    }
}
