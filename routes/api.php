<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

Route::post("/register", [AuthenticationController::class, "register"])->name("user.register");
Route::post("/login", [AuthenticationController::class, "login"])->name("user.login");

Route::middleware("auth:sanctum")
    ->post("products", [ProductController::class, "store"])
    ->name("products.store");

Route::get("products", [ProductController::class, "index"])->name("products.index");

Route::middleware("auth:sanctum")
    ->post("comments", [CommentController::class, "store"])
    ->name("comments.store");
