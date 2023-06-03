<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidUserCredentialException;
use App\Http\Requests\AuthenticationController\LoginRequest;
use App\Http\Requests\AuthenticationController\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        $token = $user->createToken(config("app.name"), []);

        return response()->json([
            "user" => new UserResource($user),
            "token" => $token->plainTextToken,
        ]);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return abort(403, "invalid user credential");
        }
        $user = auth()->user();
        $token = $user->createToken(config("app.name"), []);

        return response()->json([
            "user" => new UserResource($user),
            "token" => $token->plainTextToken,
        ]);
    }
}
