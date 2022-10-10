<?php

namespace Patabugen\MssqlChanges;

use Patabugen\MssqlChanges\Console\Commands\EnableDatabaseChangeTrackingCommand;
use Patabugen\MssqlChanges\Console\Commands\EnableTableChangeTrackingCommand;
use Patabugen\MssqlChanges\Console\Commands\GetVersionCommand;
use Patabugen\MssqlChanges\Console\Commands\ListTableChangesCommand;
use Patabugen\MssqlChanges\Console\Commands\ListTablesCommand;
use Patabugen\MssqlChanges\Console\Commands\ShowChangesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MssqlChangesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('mssql-changes')
            ->hasConfigFile()
            ->hasCommands([
                ListTablesCommand::class,
                ListTableChangesCommand::class,
                ShowChangesCommand::class,
                GetVersionCommand::class,
                EnableDatabaseChangeTrackingCommand::class,
                EnableTableChangeTrackingCommand::class,
            ])
            ->hasMigration(__DIR__.'/../tests/fixtures/database/create_contacts_table.php');
    }
}
