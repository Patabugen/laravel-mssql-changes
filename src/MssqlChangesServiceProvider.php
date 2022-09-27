<?php

namespace Patabugen\MssqlChanges;

use Patabugen\MssqlChanges\Actions\ListDatabases;
use Patabugen\MssqlChanges\Actions\ListTableChanges;
use Patabugen\MssqlChanges\Actions\ListTables;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MssqlChangesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('mssql-changes')
            ->hasConfigFile()
            ->hasCommand(ListDatabases::class)
            ->hasCommand(ListTables::class)
            ->hasCommand(ListTableChanges::class);
    }
}
