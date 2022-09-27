<?php

namespace  Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class ListChanges extends BaseAction
{
    public string $commandSignature = 'mssql:list-changes';

    public function handle(): Collection
    {
        $allChanges = new Collection;
        $tables = $this->getTables();
        $tables->each(function ($tableName) use ($allChanges) {
            logger('Searching for differences in '.$tableName);
            $changes = $this->getChanges($tableName);
            $allChanges[$tableName] = $changes;
        });

        return $allChanges;
    }

    private function getChanges(string $tableName): Collection
    {
        return new Collection;
    }

    private function getTables()
    {
    }

    public function getDatabases()
    {
        return $this->connection()
            ->table('sys.change_tracking_databases')
            ->selectRaw('DB_NAME(database_id) AS NAME, retention_period_units, retention_period_units_desc')
            ->get();
    }

    private function connection()
    {
        return DB::connection('enterprise_mrm');
    }

    public function asCommand(Command $command): void
    {
        $changes = $this->handle();
        ray($changes);
    }
}
