<?php

namespace Patabugen\MssqlChanges;

use Patabugen\MssqlChanges\Commands\MssqlChangesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->name('mssql-changes')
            ->hasConfigFile()
            ->hasCommand(MssqlChangesCommand::class);
    }
}
