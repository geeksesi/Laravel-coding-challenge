<?php

namespace App\Services;

use App\Actions\FileStateHandler\NewStateAction;
use App\Models\Product;

class ProductService
{
    public function __construct(protected NewStateAction $newStateAction)
    {
    }

    public function storeInProductComments(Product $product)
    {
        $text = sprintf("%s:%d", $product->name, 0);
        $filePath = config("app.product-comments-store-path");
        $this->newStateAction->execute($filePath, $text);
    }
}
