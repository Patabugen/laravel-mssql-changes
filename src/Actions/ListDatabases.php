<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Patabugen\MssqlChanges\Database;

class ListDatabases extends BaseAction
{
    public string $commandSignature = 'mssql:list-databases';

    public function handle(): Collection
    {
        $databases = $this->connection()
            ->table('sys.change_tracking_databases')
            ->selectRaw('DB_NAME(database_id) AS name, retention_period_units, retention_period_units_desc')
            ->get()
            ->map(function ($item) {
                return new Database(
                    $this->connection(), $item->name, $item->retention_period_units, $item->retention_period_units_desc
                );
            });

        return $databases;
    }

    public function asCommand(Command $command): void
    {
        $changes = $this->handle();
        $command->table(
            ['Database Name', 'Retention Period'],
            $changes->toArray()
        );
    }
}
