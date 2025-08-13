<?php

namespace Tests\Unit;

use Tests\TestCase;

class RouteGenerationTest extends TestCase
{
    public function test_product_view_route_generation_with_locale_falls_back_to_query_param()
    {
        $url = route('product.view', ['locale' => 'en', 'slug' => 'my-product'], false);
        $this->assertSame('/products/my-product?locale=en', $url);
    }

    public function test_product_view_route_generation_without_locale_uses_plain_path()
    {
        $url = route('product.view', ['slug' => 'my-product'], false);
        $this->assertSame('/products/my-product', $url);
    }

    public function test_catalog_route_generation_with_locale_is_prefixed()
    {
        $url = route('catalog.view', ['locale' => 'pl'], false);
        $this->assertSame('/uk/catalog', $url);
    }

    public function test_home_route_generation_with_locale_is_prefixed()
    {
        $url = route('home', ['locale' => 'en'], false);
        $this->assertSame('/en', $url);
    }
}
