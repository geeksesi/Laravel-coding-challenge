<?php

namespace App\Services;

use App\Actions\FileStateHandler\NewStateAction;
use App\Actions\FileStateHandler\UpdateStateAction;
use App\Models\Product;

class ProductService
{
    public function __construct(
        protected NewStateAction $newStateAction,
        protected UpdateStateAction $updateStateAction
    ) {
    }

    public function storeInProductComments(Product $product)
    {
        $text = sprintf("%s:%d", $product->name, 0);
        $filePath = config("app.product-comments-store-path");
        $this->newStateAction->execute($filePath, $text);
    }

    public function updateCommentsCountInProductComments(Product $product)
    {
        $commentCounts = $product->comment()->count();
        $finalText = sprintf("%s:%d", $product->name, $commentCounts);

        $filePath = config("app.product-comments-store-path");
        $this->updateStateAction->execute($filePath, $product->name, $finalText);
    }
}
