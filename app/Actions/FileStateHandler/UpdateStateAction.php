<?php

namespace App\Actions\FileStateHandler;

use App\Models\Product;
use App\Services\ProductService;

class UpdateStateAction
{
    public function __construct(protected NewStateAction $newStateAction, protected GetStateAction $getStateAction)
    {
    }

    public function execute(string $filePath, string $key, string $text)
    {
        $this->whatIfStateDoesNotAvailable($filePath, $key, $text);
        $command = sprintf('sed -i "/%s:/c\%s" %s', $key, $text, $filePath);
        exec($command);
    }

    private function whatIfStateDoesNotAvailable($filePath, string $key, string $text)
    {
        if (!is_null($this->getStateAction->execute($filePath, $key))) {
            return;
        }
        $this->newStateAction->execute($filePath, $text);
    }
}
