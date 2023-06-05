<?php

namespace Tests\Unit\Actions\FileStateHandler;

use App\Actions\FileStateHandler\GetStateAction;
use App\Actions\FileStateHandler\NewStateAction;
use App\Actions\FileStateHandler\UpdateStateAction;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateStateActionTest extends TestCase
{
    private UpdateStateAction $action;
    private NewStateAction $newStateAction;
    private GetStateAction $getStateAction;
    private string $filePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(UpdateStateAction::class);
        $this->newStateAction = app(NewStateAction::class);
        $this->getStateAction = app(GetStateAction::class);
        $this->filePath = storage_path("stateTestFile.txt");
        $this->cleanUp();
    }

    private function cleanUp()
    {
        exec(sprintf("rm -f %s", $this->filePath));
        $this->assertFileDoesNotExist($this->filePath);
    }

    public function test_should_create_file_if_does_not_exists()
    {
        $product = Product::factory()->make();

        $this->action->execute($this->filePath, $product->name, sprintf("%s:%d", $product->name, 3));

        $this->assertFileExists($this->filePath);
    }

    public function test_should_store_state_if_the_state_does_not_exists()
    {
        $this->newStateAction->execute($this->filePath, sprintf("%s:%d", "random", 1));
        $this->newStateAction->execute($this->filePath, sprintf("%s:%d", "ranssdom", 1));

        $product = Product::factory()->make();
        $state = sprintf("%s:%d", $product->name, 3);
        $this->action->execute($this->filePath, $product->name, $state);

        $actualState = $this->getStateAction->execute($this->filePath, $product->name);
        $this->assertEquals($state, $actualState);
    }

    public function test_should_update_state()
    {
        $product = Product::factory()->make();
        $oldState = sprintf("%s:%d", $product->name, 1);
        $this->newStateAction->execute($this->filePath, $oldState);

        $state = sprintf("%s:%d", $product->name, 3);
        $this->action->execute($this->filePath, $product->name, $state);

        $actualState = $this->getStateAction->execute($this->filePath, $product->name);
        $this->assertNotEquals($oldState, $actualState);
        $this->assertEquals($state, $actualState);
    }
}
