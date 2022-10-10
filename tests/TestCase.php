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

        $this->runLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__.'/fixtures/database/migrations');
        $this->enableChangeTracking();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Patabugen\\MssqlChanges\\Tests\\Fixtures\Database\\Factories\\'.class_basename($modelName)
                .'Factory'
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
        $this->setDatabaseConfig();

        $this->enableChangeTracking();
    }

    private function enableChangeTracking()
    {
        EnableDatabaseChangeTracking::run();
        EnableTableChangeTracking::run('Contacts');
    }

    public function setDatabaseConfig()
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
}
