<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::prefix('v1')->group(function () {
    
    // Reviews API
    Route::prefix('reviews')->group(function () {
        Route::get('/', [ReviewController::class, 'index']);
        Route::post('/', [ReviewController::class, 'store']);
        Route::get('/recent', [ReviewController::class, 'recent']);
        Route::get('/statistics', [ReviewController::class, 'statistics']);
        Route::get('/rating/{rating}', [ReviewController::class, 'byRating'])
             ->where('rating', '[1-5]');
    });
    
    // Blog API
    Route::prefix('blog')->group(function () {
        Route::get('/', [BlogController::class, 'index']);
        Route::get('/recent', [BlogController::class, 'recent']);
        Route::get('/search', [BlogController::class, 'search']);
        Route::get('/categories', [BlogController::class, 'categories']);
        Route::get('/{slug}', [BlogController::class, 'show']);
    });
    
    // Products API
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/featured', [ProductController::class, 'featured']);
        Route::get('/search', [ProductController::class, 'search']);
        Route::get('/collection/{id}', [ProductController::class, 'byCollection'])
             ->where('id', '[0-9]+');
        Route::get('/{slug}', [ProductController::class, 'show']);
        Route::get('/{slug}/similar', [ProductController::class, 'similar']);
    });
    
    // Health check endpoint
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now()->toISOString(),
            'version' => '1.0.0',
        ]);
    });
    
});

// Rate limiting for API routes
Route::middleware(['throttle:api'])->group(function () {
    // All API routes above are automatically included
});
