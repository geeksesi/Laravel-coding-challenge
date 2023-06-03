<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_register_with_valid_data(): void
    {
        $data = User::factory()->make();
        $data = $data->toArray();
        $data["password"] = "password";
        $response = $this->postJson(route("user.register"), $data);

        $response->assertStatus(200);
        $response->assertJsonStructure(["user", "token"]);
        $response->assertJsonPath("user.email", $data["email"]);
    }

    public function test_it_cannot_register_with_duplicate_email(): void
    {
        $existingUser = User::factory()->create();
        $data = User::factory()->make();
        $data = $data->toArray();
        $data["password"] = "password";
        $data["email"] = $existingUser->email;
        $response = $this->postJson(route("user.register"), $data);

        $response->assertInvalid(["email"]);
    }

    public function test_it_cannot_register_with_duplicate_username(): void
    {
        $existingUser = User::factory()->create();
        $data = User::factory()->make();
        $data = $data->toArray();
        $data["password"] = "password";
        $data["username"] = $existingUser->username;
        $response = $this->postJson(route("user.register"), $data);

        $response->assertInvalid(["username"]);
    }

    public function test_user_can_login_with_username_and_password(): void
    {
        $user = User::factory()->create();
        $data = [
            "username" => $user->username,
            "password" => "password",
        ];
        $response = $this->postJson(route("user.login"), $data);

        $response->assertStatus(200);
        $response->assertJsonStructure(["user", "token"]);
        $response->assertJsonPath("user.email", $user->email);
    }

    public function test_user_cannot_login_with_username_and_wrong_password(): void
    {
        $user = User::factory()->create();
        $data = [
            "username" => $user->username,
            "password" => "wrong password",
        ];
        $response = $this->postJson(route("user.login"), $data);

        $response->assertStatus(403);
    }

    public function test_user_cannot_login_with_invalid_username(): void
    {
        $user = User::factory()->create();
        $data = [
            "username" => $user->username . "WRONG",
            "password" => "wrong password",
        ];
        $response = $this->postJson(route("user.login"), $data);

        $response->assertInvalid("username");
    }
}
