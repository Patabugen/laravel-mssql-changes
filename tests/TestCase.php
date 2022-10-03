<?php

namespace Patabugen\MssqlChanges\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Patabugen\MssqlChanges\Actions\EnableDatabaseChangeTracking;
use Patabugen\MssqlChanges\MssqlChangesServiceProvider;

abstract class TestCase extends Orchestra
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
        $this->setDatabaseConfig();
        $this->enableChangeTracking();
        $migration = include __DIR__.'/../database/migrations/create_contacts_table.php';
        $migration->up();
    }

    private function enableChangeTracking()
    {
        EnableDatabaseChangeTracking::run();
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
