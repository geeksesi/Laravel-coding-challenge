<?php

namespace App\Actions\FileStateHandler;

class NewStateAction
{
    public function execute(string $filePath, string $state)
    {
        $this->createPathIfNotExists($filePath);
        $command = sprintf("echo \"%s\" >> %s", $state, $filePath);
        exec($command);
    }

    private function createPathIfNotExists($filePath)
    {
        $explodedPath = explode("/", $filePath);
        $lastKey = count($explodedPath) - 1;
        unset($explodedPath[$lastKey]);
        $directoryPath = implode("/", $explodedPath);

        if (is_dir($directoryPath)) {
            return;
        }

        $command = sprintf("mkdir -p %s ", $directoryPath);
        exec($command, $output);
        dd($output);
    }
}
