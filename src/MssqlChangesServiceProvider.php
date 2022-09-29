<?php

namespace Patabugen\MssqlChanges;

use Lorisleiva\Actions\Facades\Actions;
use Patabugen\MssqlChanges\Actions\ListTableChanges;
use Patabugen\MssqlChanges\Actions\ListDatabases;
use Patabugen\MssqlChanges\Actions\ListTables;
use Patabugen\MssqlChanges\Console\Commands\ListTablesCommand;
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
            ]);
    }
}
