<?php

namespace Tests\Feature\Http\Controllers;

use App\Actions\FileStateHandler\GetStateAction;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private GetStateAction $getStateAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->getStateAction = app(GetStateAction::class);
    }

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

    public function test_as_guest_can_get_list_of_products()
    {
        $products = Product::factory(3)
            ->has(
                Comment::factory()
                    ->forUser()
                    ->count(2)
            )
            ->create();
        $response = $this->getJson(route("products.index"));

        $response->assertSuccessful();

        $response->assertJsonStructure([
            "data" => [
                "*" => [
                    "id",
                    "name",
                    "comments_count",
                    "comments" => [
                        "*" => [
                            "user" => ["name"],
                            "comment",
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function test_comments_count_should_be_equal_with_stored_state()
    {
        Product::factory(3)
            ->has(
                Comment::factory()
                    ->forUser()
                    ->count(2)
            )
            ->create();
        $response = $this->getJson(route("products.index"));

        $response->assertSuccessful();

        foreach ($response->json("data") as $product) {
            $state = $this->getStateAction->execute(config("app.product-comments-store-path"), $product["name"]);
            $this->assertStringContainsString($product["comments_count"], $state);
        }
    }
}
