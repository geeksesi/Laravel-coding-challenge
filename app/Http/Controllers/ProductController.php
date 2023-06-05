<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductController\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $products = Product::with(["comments", "comments.user"])
            ->withCount(["comments"])
            ->paginate();
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        return response("", 201);
    }
}
