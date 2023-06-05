<?php

namespace App\Actions\FileStateHandler;

use App\Models\Product;
use App\Services\ProductService;

class GetStateAction
{
    public function execute(string $filePath, string $key): null|string
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $command = sprintf('grep "%s" %s', $key, $filePath);
        exec($command, $output);
        return $output[0] ?? null;
    }
}
