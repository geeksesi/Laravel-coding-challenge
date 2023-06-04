<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanUp();
    }

    private function cleanUp()
    {
        exec(sprintf("rm -f %s", config("app.product-comments-store-path")));
    }
}
