<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Services\ProductService;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    private ProductService $service;
    private string $filePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ProductService::class);
    }

    public function test_it_can_store_new_product_state()
    {
        $product = Product::factory()->make(["name" => "test product"]);
        $this->service->storeInProductComments($product);

        $this->assertFileEquals(config("app.product-comments-store-path"), storage_path("tests/new-product-state.txt"));
    }
}
