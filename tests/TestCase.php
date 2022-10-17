<?php

namespace Patabugen\MssqlChanges\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Patabugen\MssqlChanges\Actions\EnableDatabaseChangeTracking;
use Patabugen\MssqlChanges\Actions\EnableTableChangeTracking;
use Patabugen\MssqlChanges\MssqlChangesServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->enableChangeTracking();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Patabugen\\MssqlChanges\\Tests\\Fixtures\Database\\Factories\\'.class_basename($modelName)
                .'Factory'
        );
    }

    protected function defineDatabaseMigrations()
    {
        $this->artisan('migrate:fresh')->run();
        $this->loadMigrationsFrom(__DIR__.'/fixtures/database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            MssqlChangesServiceProvider::class,
            \Spatie\LaravelRay\RayServiceProvider::class,
        ];
    }

    public function defineEnvironment($app)
    {
        config()->set('database.connections.default', [
            'driver' => env('DB_DRIVER', 'sqlsrv'),
            'host' => env('DB_HOST', 'sqlsrv'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'LaravelMssqlChangesTest'),
            'username' => env('DB_USERNAME', 'sa'),
            'password' => env('DB_PASSWORD', 'password'),
            'url' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'trust_server_certificate' => true,
            'options' => [
                'TrustServerCertificate' => '1',
            ],
        ]);
    }

    /**
     * Enable change-tracking on our test database and tables. We can use the
     * DisableTableChangeTracking in tests to disable them if needed.
     */
    private function enableChangeTracking()
    {
        EnableDatabaseChangeTracking::run();
        EnableTableChangeTracking::run('Contacts');
        EnableTableChangeTracking::run('Addresses');
    }
}
