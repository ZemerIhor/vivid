<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Repositories\ReviewRepositoryInterface;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository,
        private ReviewService $reviewService
    ) {}

    /**
     * Get published reviews with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 10), 50); // Max 50 per page
        $reviews = $this->reviewRepository->getPublishedPaginated($perPage);

        return response()->json([
            'data' => $reviews->items(),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    /**
     * Store a new review
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        try {
            $reviewData = [
                'name' => $request->validated('name'),
                'rating' => $request->validated('rating'),
                'comment' => [
                    app()->getLocale() => $request->validated('comment')
                ],
            ];

            $review = $this->reviewService->createReview($reviewData);

            return response()->json([
                'message' => 'Review submitted successfully',
                'data' => [
                    'id' => $review->id,
                    'name' => $review->name,
                    'rating' => $review->rating,
                    'published' => $review->published,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to submit review',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Get reviews by rating
     */
    public function byRating(Request $request, int $rating): JsonResponse
    {
        if ($rating < 1 || $rating > 5) {
            return response()->json([
                'message' => 'Rating must be between 1 and 5',
            ], 400);
        }

        $reviews = $this->reviewRepository->getByRating($rating);

        return response()->json([
            'data' => $reviews,
            'meta' => [
                'rating' => $rating,
                'total' => $reviews->count(),
            ],
        ]);
    }

    /**
     * Get review statistics
     */
    public function statistics(): JsonResponse
    {
        $stats = $this->reviewService->getRatingStatistics();
        $averageRating = $this->reviewService->getAverageRating();

        return response()->json([
            'data' => [
                'average_rating' => $averageRating,
                'total_reviews' => $stats['total_reviews'],
                'rating_distribution' => $stats['ratings'],
            ],
        ]);
    }

    /**
     * Get recent reviews
     */
    public function recent(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 5), 20); // Max 20
        $reviews = $this->reviewService->getRecentReviews($limit);

        return response()->json([
            'data' => $reviews,
            'meta' => [
                'limit' => $limit,
                'total' => $reviews->count(),
            ],
        ]);
    }
}
