<?php

namespace Tests\Unit\Actions\FileStateHandler;

use App\Actions\FileStateHandler\NewStateAction;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewStateActionTest extends TestCase
{
    private NewStateAction $action;
    private string $filePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(NewStateAction::class);
        $this->filePath = storage_path("stateTestFile.txt");
        $this->cleanUp();
    }

    private function cleanUp()
    {
        exec(sprintf("rm -f %s", $this->filePath));
        $this->assertFileDoesNotExist($this->filePath);
    }

    public function test_should_create_file_if_not_exists(): void
    {
        $this->assertFileDoesNotExist($this->filePath);

        $this->action->execute($this->filePath, "hello");

        $this->assertFileExists($this->filePath);
    }

    public function test_new_content_should_be_in_new_line(): void
    {
        $this->action->execute($this->filePath, "hello");
        $this->action->execute($this->filePath, "i am new line");

        $this->assertFileEquals($this->filePath, storage_path("tests/new-content-new-line-test.txt"));
    }
}
