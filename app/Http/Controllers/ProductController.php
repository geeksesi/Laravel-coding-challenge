<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductController\StoreProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        //
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        return response("", 201);
    }
}
