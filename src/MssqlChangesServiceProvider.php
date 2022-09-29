<?php

namespace Patabugen\MssqlChanges;

use Patabugen\MssqlChanges\Console\Commands\ListTableChangesCommand;
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
                ListTableChangesCommand::class,
            ]);
    }
}
