<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_user_can_create_new_product(): void
    {
        $product = Product::factory()->make();
        $response = $this->actingAs(User::factory()->create())->postJson(route("products.store"), $product->toArray());
        $response->assertStatus(201);

        $this->assertDatabaseCount(app(Product::class)->getTable(), 1);
    }

    /**
     * A basic feature test example.
     */
    public function test_as_guest_cannot_create_new_product(): void
    {
        $product = Product::factory()->make();
        $response = $this->postJson(route("products.store"), $product->toArray());
        $response->assertUnauthorized();
    }
}
