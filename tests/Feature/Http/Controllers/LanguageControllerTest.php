<?php

namespace Tests\Feature\Http\Controllers;

use App\Services\LanguageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Mockery;

class LanguageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Log::spy();
    }

    public function test_switch_language_successful_redirect()
    {
        $response = $this->get('/lang/en?redirect_to=/products');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
    }

    public function test_switch_language_with_invalid_locale_logs_warning()
    {
        $response = $this->get('/lang/invalid');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Invalid locale');
        
        Log::shouldHaveReceived('warning')
            ->once()
            ->with('Invalid locale attempted in language switch', [
                'locale' => 'invalid',
                'user_ip' => '127.0.0.1',
            ]);
    }

    public function test_switch_language_handles_database_exception()
    {
        $mockService = Mockery::mock(LanguageService::class);
        $mockService->shouldReceive('switchLanguage')
            ->andThrow(new \Illuminate\Database\QueryException(
                'mysql', 
                'SELECT * FROM urls', 
                [], 
                new \Exception('Connection failed')
            ));
        
        $this->app->instance(LanguageService::class, $mockService);

        $response = $this->get('/lang/en');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Language switch temporarily unavailable');
        
        Log::shouldHaveReceived('error')
            ->once()
            ->with('Database error during language switch', Mockery::type('array'));
    }

    public function test_switch_language_handles_general_exception()
    {
        $mockService = Mockery::mock(LanguageService::class);
        $mockService->shouldReceive('switchLanguage')
            ->andThrow(new \RuntimeException('Unexpected error'));
        
        $this->app->instance(LanguageService::class, $mockService);

        $response = $this->get('/lang/en');

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Language switch failed');
        
        Log::shouldHaveReceived('error')
            ->once()
            ->with('Unexpected error during language switch', Mockery::on(function ($data) {
                return isset($data['locale']) && 
                       isset($data['error']) && 
                       isset($data['file']) && 
                       isset($data['line']);
            }));
    }

    public function test_switch_language_adds_query_parameters()
    {
        $mockService = Mockery::mock(LanguageService::class);
        $mockService->shouldReceive('switchLanguage')
            ->with('en', 'http://localhost/products?page=2')
            ->andReturn('/en/products');
        $mockService->shouldReceive('addQueryParameters')
            ->with('/en/products', 'http://localhost/products?page=2')
            ->andReturn('/en/products?page=2');
        
        $this->app->instance(LanguageService::class, $mockService);

        $response = $this->get('/lang/en?redirect_to=http://localhost/products?page=2');

        $response->assertRedirect('/en/products?page=2');
    }

    public function test_switch_language_fallback_to_locale_path()
    {
        $mockService = Mockery::mock(LanguageService::class);
        $mockService->shouldReceive('switchLanguage')
            ->andReturn('');
        $mockService->shouldReceive('addQueryParameters')
            ->andReturn('');
        
        $this->app->instance(LanguageService::class, $mockService);

        $response = $this->get('/lang/en');

        $response->assertRedirect('/en');
    }

    public function test_switch_language_logs_user_ip_for_invalid_attempts()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.100']);
        
        $response = $this->get('/lang/invalid');

        Log::shouldHaveReceived('warning')
            ->once()
            ->with('Invalid locale attempted in language switch', [
                'locale' => 'invalid',
                'user_ip' => '192.168.1.100',
            ]);
    }

    public function test_switch_language_preserves_redirect_to_parameter()
    {
        $redirectUrl = 'http://localhost/products/laptop';
        
        $response = $this->get("/lang/en?redirect_to={$redirectUrl}");

        $response->assertRedirect();
    }

    public function test_switch_language_uses_full_url_when_no_redirect_to()
    {
        $response = $this->get('/lang/en');

        $response->assertRedirect();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
