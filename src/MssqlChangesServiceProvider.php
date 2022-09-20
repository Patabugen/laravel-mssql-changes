<?php

namespace Patabugen\MssqlChanges;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Patabugen\MssqlChanges\Commands\MssqlChangesCommand;

class MssqlChangesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-mssql-changes')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-mssql-changes_table')
            ->hasCommand(MssqlChangesCommand::class);
    }
}
