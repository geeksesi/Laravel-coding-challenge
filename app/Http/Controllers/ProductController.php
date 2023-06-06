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

    /**
     * List of Products with comments
     *
     * @group Product
     */
    public function index()
    {
        $products = Product::with(["comments", "comments.user"])
            ->withCount(["comments"])
            ->paginate();
        return ProductResource::collection($products);
    }

    /**
     * Create Product
     * create a product just with a name
     *
     * @response 201
     *
     * @group Product
     * @authenticated
     */
    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        return response("", 201);
    }
}
