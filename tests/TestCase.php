<?php

namespace Patabugen\MssqlChanges\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Patabugen\MssqlChanges\MssqlChangesServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Patabugen\\MssqlChanges\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            MssqlChangesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
<<<<<<< Updated upstream
        // config()->set('database.default', 'testing');
=======
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-mssql-changes_table.php.stub';
        $migration->up();
        */
>>>>>>> Stashed changes
    }
}
