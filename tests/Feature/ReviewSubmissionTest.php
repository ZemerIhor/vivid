<?php

namespace Tests\Feature;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_review_with_valid_data()
    {
        // Arrange
        $reviewData = [
            'name' => 'John Doe',
            'rating' => 5,
            'comment' => 'Excellent product! Highly recommend it.',
        ];

        // Act
        $response = $this->post(route('reviews.store'), $reviewData);

        // Assert
        $response->assertStatus(302); // Redirect after successful submission
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('reviews', [
            'name' => 'John Doe',
            'rating' => 5,
            'published' => false, // Should be unpublished by default
        ]);
    }

    public function test_review_submission_requires_all_fields()
    {
        // Act
        $response = $this->post(route('reviews.store'), []);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name', 'rating', 'comment']);
    }

    public function test_review_name_must_be_at_least_2_characters()
    {
        // Arrange
        $reviewData = [
            'name' => 'A',
            'rating' => 5,
            'comment' => 'Good product',
        ];

        // Act
        $response = $this->post(route('reviews.store'), $reviewData);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }

    public function test_review_rating_must_be_between_1_and_5()
    {
        // Arrange
        $reviewData = [
            'name' => 'John Doe',
            'rating' => 6, // Invalid rating
            'comment' => 'Good product',
        ];

        // Act
        $response = $this->post(route('reviews.store'), $reviewData);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['rating']);
    }

    public function test_review_comment_must_be_at_least_10_characters()
    {
        // Arrange
        $reviewData = [
            'name' => 'John Doe',
            'rating' => 5,
            'comment' => 'Short', // Too short
        ];

        // Act
        $response = $this->post(route('reviews.store'), $reviewData);

        // Assert
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['comment']);
    }

    public function test_reviews_page_displays_only_published_reviews()
    {
        // Arrange
        Review::create([
            'name' => 'Published Review',
            'rating' => 5,
            'comment' => ['en' => 'This review is published'],
            'published' => true,
            'published_at' => now(),
        ]);

        Review::create([
            'name' => 'Unpublished Review',
            'rating' => 4,
            'comment' => ['en' => 'This review is not published'],
            'published' => false,
            'published_at' => null,
        ]);

        // Act
        $response = $this->get(route('reviews'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Published Review');
        $response->assertDontSee('Unpublished Review');
    }

    public function test_review_submission_page_loads_correctly()
    {
        // Act
        $response = $this->get(route('submit-review'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Submit Review'); // Assuming this text exists on the page
    }

    public function test_review_stores_comment_in_current_locale()
    {
        // Arrange
        app()->setLocale('en');
        
        $reviewData = [
            'name' => 'Jane Doe',
            'rating' => 4,
            'comment' => 'Great product with fast delivery!',
        ];

        // Act
        $response = $this->post(route('reviews.store'), $reviewData);

        // Assert
        $review = Review::where('name', 'Jane Doe')->first();
        $this->assertNotNull($review);
        $this->assertEquals('Great product with fast delivery!', $review->getTranslation('comment', 'en'));
    }

    public function test_multiple_reviews_can_be_submitted()
    {
        // Arrange & Act
        for ($i = 1; $i <= 3; $i++) {
            $this->post(route('reviews.store'), [
                'name' => "User {$i}",
                'rating' => $i + 2, // 3, 4, 5
                'comment' => "Comment from user {$i}",
            ]);
        }

        // Assert
        $this->assertDatabaseCount('reviews', 3);
        $this->assertDatabaseHas('reviews', ['name' => 'User 1', 'rating' => 3]);
        $this->assertDatabaseHas('reviews', ['name' => 'User 2', 'rating' => 4]);
        $this->assertDatabaseHas('reviews', ['name' => 'User 3', 'rating' => 5]);
    }
}
