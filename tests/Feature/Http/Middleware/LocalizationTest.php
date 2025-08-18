<?php

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\Localization;
use App\Services\LanguageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Mockery;

class LocalizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Log::spy();
    }

    public function test_middleware_sets_locale_from_url()
    {
        $request = Request::create('/pl/products');
        $middleware = new Localization();

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('pl', app()->getLocale());
        $this->assertEquals('OK', $response->getContent());
    }

    public function test_middleware_logs_only_in_debug_mode()
    {
        config(['app.debug' => true]);
        
        $request = Request::create('/en/catalog');
        $middleware = new Localization();

        $middleware->handle($request, function ($req) {
            return response('OK');
        });

        Log::shouldHaveReceived('debug')
            ->once()
            ->with('Localization Middleware', Mockery::type('array'));
    }

    public function test_middleware_does_not_log_in_production()
    {
        config(['app.debug' => false]);
        
        $request = Request::create('/en/catalog');
        $middleware = new Localization();

        $middleware->handle($request, function ($req) {
            return response('OK');
        });

        Log::shouldNotHaveReceived('debug');
        Log::shouldNotHaveReceived('info');
    }

    public function test_middleware_handles_invalid_locale()
    {
        $request = Request::create('/invalid/products');
        $middleware = new Localization();

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        // Should fallback to default locale
        $this->assertEquals('en', app()->getLocale());
    }

    public function test_middleware_logs_correct_data_structure()
    {
        config(['app.debug' => true]);
        
        $request = Request::create('/pl/products/laptop');
        $middleware = new Localization();

        $middleware->handle($request, function ($req) {
            return response('OK');
        });

        Log::shouldHaveReceived('debug')
            ->once()
            ->with('Localization Middleware', Mockery::on(function ($data) {
                return isset($data['url_locale']) &&
                       isset($data['detected_locale']) &&
                       isset($data['final_locale']) &&
                       isset($data['path']) &&
                       $data['url_locale'] === 'pl' &&
                       $data['path'] === 'pl/products/laptop';
            }));
    }

    public function test_middleware_works_with_root_path()
    {
        $request = Request::create('/en');
        $middleware = new Localization();

        $response = $middleware->handle($request, function ($req) {
            return response('OK');
        });

        $this->assertEquals('en', app()->getLocale());
        $this->assertEquals('OK', $response->getContent());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
