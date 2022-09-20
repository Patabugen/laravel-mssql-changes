<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;

class ListDatabases extends BaseAction
{
    use AsAction;

    public string $commandSignature = 'mssql:list-databases';

    public function handle(): Collection
    {
        $databases = $this->connection()
            ->table('sys.change_tracking_databases')
            ->selectRaw('DB_NAME(database_id) AS NAME, retention_period_units, retention_period_units_desc')
            ->get();

        return $databases;
    }

    public function asCommand(Command $command): void
    {
        $changes = $this->handle();
        ray($changes);
    }
}
