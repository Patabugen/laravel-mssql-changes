<?php
namespace Patabugen\MssqlChanges;

use Patabugen\MssqlChanges\Commands\MssqlChangesCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MssqlChangesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('mssql-changes')
            ->hasConfigFile()
            ->hasCommand(MssqlChangesCommand::class);
    }
}
