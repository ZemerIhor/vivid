<?php

namespace Tests\Unit\Services;

use App\Services\LanguageService;
use Tests\TestCase;

class LanguageServiceTest extends TestCase
{
    private LanguageService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LanguageService();
    }

    public function test_is_valid_locale_returns_true_for_supported_locales()
    {
        $this->assertTrue($this->service->isValidLocale('en'));
        $this->assertTrue($this->service->isValidLocale('pl'));
    }

    public function test_is_valid_locale_returns_false_for_unsupported_locales()
    {
        $this->assertFalse($this->service->isValidLocale('fr'));
        $this->assertFalse($this->service->isValidLocale('de'));
        $this->assertFalse($this->service->isValidLocale('invalid'));
    }

    public function test_switch_language_throws_exception_for_invalid_locale()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid locale');
        
        $this->service->switchLanguage('invalid');
    }

    public function test_get_supported_locales_returns_correct_array()
    {
        $expected = ['en', 'pl'];
        $this->assertEquals($expected, $this->service->getSupportedLocales());
    }

    public function test_add_query_parameters_adds_query_string()
    {
        $path = '/en/products';
        $originalUrl = 'https://example.com/pl/products?page=2&sort=name';
        
        $result = $this->service->addQueryParameters($path, $originalUrl);
        
        $this->assertEquals('/en/products?page=2&sort=name', $result);
    }

    public function test_add_query_parameters_without_query_string()
    {
        $path = '/en/products';
        $originalUrl = 'https://example.com/pl/products';
        
        $result = $this->service->addQueryParameters($path, $originalUrl);
        
        $this->assertEquals('/en/products', $result);
    }

    public function test_get_current_locale_returns_app_locale()
    {
        app()->setLocale('en');
        $this->assertEquals('en', $this->service->getCurrentLocale());
        
        app()->setLocale('pl');
        $this->assertEquals('pl', $this->service->getCurrentLocale());
    }

    public function test_get_locale_from_request_returns_session_locale_when_no_url_locale()
    {
        // Simulate session locale
        session(['locale' => 'pl']);
        
        // Mock request without locale in URL
        $this->mock('request', function ($mock) {
            $mock->shouldReceive('segment')->with(1)->andReturn('products');
        });
        
        $result = $this->service->getLocaleFromRequest();
        
        $this->assertEquals('pl', $result);
    }
}
