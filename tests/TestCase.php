<?php

namespace Patabugen\MssqlChanges\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Patabugen\MssqlChanges\MssqlChangesServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            MssqlChangesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        // config()->set('database.default', 'testing');
    }
}
