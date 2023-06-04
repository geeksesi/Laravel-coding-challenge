<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_new_product(): void
    {
        $product = Product::factory()->make();
        $response = $this->actingAs(User::factory()->create())->postJson(route("products.store"), $product->toArray());
        $response->assertStatus(201);

        $this->assertDatabaseCount(app(Product::class)->getTable(), 1);
    }

    public function test_user_on_create_new_product_will_have_the_state_of_the_product_on_product_comments_file(): void
    {
        $product = Product::factory()->make(["name" => "test product"]);
        $response = $this->actingAs(User::factory()->create())->postJson(route("products.store"), $product->toArray());
        $response->assertStatus(201);

        $this->assertDatabaseCount(app(Product::class)->getTable(), 1);
        $this->assertFileEquals(config("app.product-comments-store-path"), storage_path("tests/new-product-state.txt"));
    }

    public function test_as_guest_cannot_create_new_product(): void
    {
        $product = Product::factory()->make();
        $response = $this->postJson(route("products.store"), $product->toArray());
        $response->assertUnauthorized();
    }
}
