<?php

namespace Excent\BePaidLaravel\Tests;

use Excent\BePaidLaravel\Providers\BePaidServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [BePaidServiceProvider::class];
    }

    protected function tearDown(): void
    {
        \Mockery::close();

        parent::tearDown();
    }
}
