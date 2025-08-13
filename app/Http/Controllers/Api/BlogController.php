<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\BlogPostRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(
        private BlogPostRepositoryInterface $blogPostRepository
    ) {}

    /**
     * Get published blog posts with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 10), 50); // Max 50 per page
        $posts = $this->blogPostRepository->getPublishedPaginated($perPage);

        return response()->json([
            'data' => $posts->items(),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    /**
     * Get a specific blog post by slug
     */
    public function show(string $slug): JsonResponse
    {
        $post = $this->blogPostRepository->findBySlug($slug);

        if (!$post) {
            return response()->json([
                'message' => 'Blog post not found',
            ], 404);
        }

        return response()->json([
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'banner' => $post->banner,
                'seo_title' => $post->seo_title,
                'seo_description' => $post->seo_description,
                'published_at' => $post->published_at?->toISOString(),
                'created_at' => $post->created_at->toISOString(),
                'updated_at' => $post->updated_at->toISOString(),
            ],
        ]);
    }

    /**
     * Search blog posts
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');
        $perPage = min($request->get('per_page', 10), 50);

        if (empty($query) || strlen($query) < 3) {
            return response()->json([
                'message' => 'Search query must be at least 3 characters long',
            ], 400);
        }

        $posts = $this->blogPostRepository->search($query, $perPage);

        return response()->json([
            'data' => $posts->items(),
            'meta' => [
                'query' => $query,
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    /**
     * Get recent blog posts
     */
    public function recent(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 5), 20); // Max 20
        $posts = $this->blogPostRepository->getRecent($limit);

        return response()->json([
            'data' => $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => $post->excerpt,
                    'banner' => $post->banner,
                    'published_at' => $post->published_at?->toISOString(),
                ];
            }),
            'meta' => [
                'limit' => $limit,
                'total' => $posts->count(),
            ],
        ]);
    }

    /**
     * Get blog post categories/tags (if implemented)
     */
    public function categories(): JsonResponse
    {
        // This would be implemented if you have categories
        // For now, return empty array
        return response()->json([
            'data' => [],
            'message' => 'Categories not implemented yet',
        ]);
    }
}
