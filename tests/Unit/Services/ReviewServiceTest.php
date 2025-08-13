<?php

namespace Tests\Unit\Services;

use App\Models\Review;
use App\Repositories\ReviewRepositoryInterface;
use App\Services\ReviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class ReviewServiceTest extends TestCase
{
    use RefreshDatabase;

    private ReviewService $service;
    private $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->repositoryMock = Mockery::mock(ReviewRepositoryInterface::class);
        $this->service = new ReviewService($this->repositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_review_success()
    {
        // Arrange
        $reviewData = [
            'name' => 'Test User',
            'rating' => 5,
            'comment' => ['en' => 'Great product!'],
        ];

        $expectedReview = new Review($reviewData);
        $expectedReview->id = 1;

        $this->repositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($reviewData)
            ->andReturn($expectedReview);

        // Act
        $result = $this->service->createReview($reviewData);

        // Assert
        $this->assertInstanceOf(Review::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Test User', $result->name);
        $this->assertEquals(5, $result->rating);
    }

    public function test_get_published_reviews()
    {
        // Arrange
        $perPage = 10;
        $mockPaginator = Mockery::mock(\Illuminate\Pagination\LengthAwarePaginator::class);

        $this->repositoryMock
            ->shouldReceive('getPublishedPaginated')
            ->once()
            ->with($perPage)
            ->andReturn($mockPaginator);

        // Act
        $result = $this->service->getPublishedReviews($perPage);

        // Assert
        $this->assertEquals($mockPaginator, $result);
    }

    public function test_get_reviews_by_rating_invalid_rating()
    {
        // Arrange & Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Rating must be between 1 and 5');
        
        $this->service->getReviewsByRating(6);
    }

    public function test_get_reviews_by_rating_valid()
    {
        // Arrange
        $rating = 5;
        $mockCollection = collect([]);

        $this->repositoryMock
            ->shouldReceive('getByRating')
            ->once()
            ->with($rating)
            ->andReturn($mockCollection);

        // Act
        $result = $this->service->getReviewsByRating($rating);

        // Assert
        $this->assertEquals($mockCollection, $result);
    }

    public function test_publish_review()
    {
        // Arrange
        $reviewId = 1;

        $this->repositoryMock
            ->shouldReceive('publish')
            ->once()
            ->with($reviewId)
            ->andReturn(true);

        // Act
        $result = $this->service->publishReview($reviewId);

        // Assert
        $this->assertTrue($result);
    }

    public function test_bulk_publish_reviews()
    {
        // Arrange
        $reviewIds = [1, 2, 3];

        $this->repositoryMock
            ->shouldReceive('publish')
            ->times(3)
            ->andReturn(true, true, false);

        // Act
        $result = $this->service->bulkPublishReviews($reviewIds);

        // Assert
        $this->assertEquals(2, $result);
    }
}
