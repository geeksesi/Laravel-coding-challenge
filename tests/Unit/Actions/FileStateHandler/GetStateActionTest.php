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

class GetStateActionTest extends TestCase
{
    private GetStateAction $action;
    private NewStateAction $newStateAction;

    private string $filePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(GetStateAction::class);
        $this->newStateAction = app(NewStateAction::class);

        $this->filePath = storage_path("stateTestFile.txt");
        $this->cleanUp();
    }

    private function cleanUp()
    {
        exec(sprintf("rm -f %s", $this->filePath));
        $this->assertFileDoesNotExist($this->filePath);
    }

    public function test_return_entire_line_of_state()
    {
        $storedState = sprintf("%s:%s", "test", "3");
        $this->newStateAction->execute($this->filePath, $storedState);

        $state = $this->action->execute($this->filePath, "test");

        $this->assertEquals($storedState, $state);
    }

    public function test_return_entire_line_of_state_on_multiple_state_exists()
    {
        $storedState = sprintf("%s:%s", "test", "3");
        $this->newStateAction->execute($this->filePath, $storedState);
        $this->newStateAction->execute($this->filePath, sprintf("%s:%s", "jj", "3"));
        $this->newStateAction->execute($this->filePath, sprintf("%s:%s", "mj", "3"));

        $state = $this->action->execute($this->filePath, "test");

        $this->assertEquals($storedState, $state);
    }

    public function test_on_file_does_not_exist_should_return_null()
    {
        $state = $this->action->execute($this->filePath, "test");

        $this->assertNull($state);
    }

    public function test_on_state_does_not_exist_should_return_null()
    {
        $storedState = sprintf("%s:%s", "javad", "3");
        $this->newStateAction->execute($this->filePath, $storedState);

        $state = $this->action->execute($this->filePath, "test");

        $this->assertNull($state);
    }
}
