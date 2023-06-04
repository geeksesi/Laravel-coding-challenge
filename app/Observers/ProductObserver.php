<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ProductService;

class ProductObserver
{
    public function __construct(protected ProductService $service)
    {
    }

    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->service->storeInProductComments($product);
    }
}
