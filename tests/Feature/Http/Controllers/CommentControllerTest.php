<?php

namespace Tests\Feature\Http\Controllers;

use App\Actions\FileStateHandler\GetStateAction;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;
    private GetStateAction $getStateAction;

    public function setUp(): void
    {
        parent::setUp();
        $this->getStateAction = app(GetStateAction::class);
    }

    public function test_as_user_it_can_create_a_comment_on_post(): void
    {
        $product = Product::factory()->create();
        $payload = [
            "product_name" => $product->name,
            "comment" => fake()->realText(),
        ];
        $response = $this->actingAs(User::factory()->create())->postJson(route("comments.store"), $payload);

        $response->assertStatus(201);

        $this->assertDatabaseCount(app(Comment::class)->getTable(), 1);
    }

    public function test_if_product_not_exists_should_create_a_product(): void
    {
        $product = Product::factory()->make();
        $payload = [
            "product_name" => $product->name,
            "comment" => fake()->realText(),
        ];
        $response = $this->actingAs(User::factory()->create())->postJson(route("comments.store"), $payload);

        $response->assertStatus(201);

        $this->assertDatabaseCount(app(Comment::class)->getTable(), 1);
        $this->assertDatabaseCount(app(Product::class)->getTable(), 1);
    }

    public function test_as_guest_cannot_create_comment(): void
    {
        $product = Product::factory()->make();
        $payload = [
            "product_name" => $product->name,
            "comment" => fake()->realText(),
        ];
        $response = $this->postJson(route("comments.store"), $payload);

        $response->assertUnauthorized();
    }

    public function test_user_cannot_create_more_than_two_comment_on_a_product(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->make();
        $payload = [
            "product_name" => $product->name,
            "comment" => fake()->realText(),
        ];
        $response = $this->actingAs($user)->postJson(route("comments.store"), $payload);
        $response->assertStatus(201);
        $response = $this->actingAs($user)->postJson(route("comments.store"), $payload);
        $response->assertStatus(201);

        $response = $this->actingAs($user)->postJson(route("comments.store"), $payload);
        $response->assertInvalid(["product_name"]);

        $this->assertDatabaseCount(app(Comment::class)->getTable(), 2);
        $this->assertDatabaseCount(app(Product::class)->getTable(), 1);
    }

    public function test_on_create_comment_state_should_be_updated(): void
    {
        $product = Product::factory()->create();
        $payload = [
            "product_name" => $product->name,
            "comment" => fake()->realText(),
        ];
        $state = $this->getStateAction->execute(config("app.product-comments-store-path"), $product->name);
        $this->assertStringContainsString("0", $state);
        $response = $this->actingAs(User::factory()->create())->postJson(route("comments.store"), $payload);
        $response->assertStatus(201);
        $response = $this->actingAs(User::factory()->create())->postJson(route("comments.store"), $payload);
        $response->assertStatus(201);

        $state = $this->getStateAction->execute(config("app.product-comments-store-path"), $product->name);
        $this->assertStringContainsString("2", $state);
        $this->assertDatabaseCount(app(Comment::class)->getTable(), 2);
    }
}
