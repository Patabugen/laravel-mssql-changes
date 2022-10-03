<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Patabugen\MssqlChanges\Database;

class GetVersion extends BaseAction
{
    public string $commandSignature = 'mssql:get-version';

    public function handle(): int
    {
        $version = $this->connection()
            ->table('sys.change_tracking_databases')
            ->selectRaw('ChangeTrackingCurrentVersion =  CHANGE_TRACKING_CURRENT_VERSION()')
            ->first()
            ->ChangeTrackingCurrentVersion;

        throw_unless (
            is_numeric($version),
            'CHANGE_TRACKING_CURRENT_VERSION() returned null, is change tracking enabled on '
            . $this->connection()->getDatabaseName().'?'
        );
        return $version;
    }

    public function asCommand(Command $command): void
    {
        $command->info($this->handle());
    }
}
