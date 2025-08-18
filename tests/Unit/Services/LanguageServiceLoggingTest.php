<?php

namespace Tests\Unit\Services;

use App\Services\LanguageService;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Mockery;

class LanguageServiceLoggingTest extends TestCase
{
    private LanguageService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LanguageService();
        Log::spy();
    }

    public function test_switch_language_logs_in_debug_mode()
    {
        config(['app.debug' => true]);
        
        try {
            $this->service->switchLanguage('en', 'http://localhost/products');
        } catch (\Exception $e) {
            // Ignore exceptions for this test
        }

        Log::shouldHaveReceived('debug')
            ->once()
            ->with('Language switch requested', Mockery::type('array'));
    }

    public function test_switch_language_does_not_log_in_production()
    {
        config(['app.debug' => false]);
        
        try {
            $this->service->switchLanguage('en', 'http://localhost/products');
        } catch (\Exception $e) {
            // Ignore exceptions for this test
        }

        Log::shouldNotHaveReceived('debug');
        Log::shouldNotHaveReceived('info');
    }

    public function test_invalid_locale_always_logs_warning()
    {
        config(['app.debug' => false]);
        
        $this->expectException(\InvalidArgumentException::class);
        
        $this->service->switchLanguage('invalid');

        Log::shouldHaveReceived('warning')
            ->once()
            ->with('Invalid locale attempted: invalid');
    }

    public function test_debug_logging_contains_correct_data()
    {
        config(['app.debug' => true]);
        
        try {
            $this->service->switchLanguage('pl', 'http://localhost/en/products?page=2');
        } catch (\Exception $e) {
            // Ignore exceptions for this test
        }

        Log::shouldHaveReceived('debug')
            ->once()
            ->with('Language switch requested', Mockery::on(function ($data) {
                return isset($data['locale']) &&
                       isset($data['redirect_to']) &&
                       isset($data['current_url']) &&
                       $data['locale'] === 'pl';
            }));
    }

    public function test_warning_logs_regardless_of_debug_mode()
    {
        config(['app.debug' => false]);
        
        $this->expectException(\InvalidArgumentException::class);
        
        $this->service->switchLanguage('fr');

        Log::shouldHaveReceived('warning')
            ->once()
            ->with('Invalid locale attempted: fr');
    }

    public function test_multiple_invalid_attempts_log_separately()
    {
        config(['app.debug' => false]);
        
        try {
            $this->service->switchLanguage('de');
        } catch (\InvalidArgumentException $e) {
            // Expected
        }
        
        try {
            $this->service->switchLanguage('fr');
        } catch (\InvalidArgumentException $e) {
            // Expected
        }

        Log::shouldHaveReceived('warning')->twice();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
