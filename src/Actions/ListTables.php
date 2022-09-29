<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Patabugen\MssqlChanges\Table;

class ListTables extends BaseAction
{
    public string $commandSignature = 'mssql:list-tables';

    public array $tableFilter = [];

    /**
     * Returns a collection of Table objects for any table which has Change Tracking
     * enabled. Does not include tables with tracking disabled.
     *
     * @return Collection
     */
    public function handle(): Collection
    {
        $query = $this->connection()
            ->table('sys.change_tracking_tables')
            ->select('sys.tables.*')
            // Link the change-tracking system info to the tables list
            ->join('sys.tables', 'sys.change_tracking_tables.object_id', 'sys.tables.object_id')
            ->orderBy('sys.tables.name')
            ->when(! empty($this->tableFilter), fn ($query) => $query->whereIn('sys.tables.name', $this->tableFilter));

        return $query->get()->mapWithKeys(function ($item) {
            $primaryKey = $this->connection()->select('EXEC sp_pkeys ?', [$item->name])[0]->COLUMN_NAME;

            $columns = $this->connection()
                ->table('INFORMATION_SCHEMA.COLUMNS')
                ->select('COLUMN_NAME')
                ->where('TABLE_NAME', $item->name)
                ->get();

            return [
                $item->name => new Table(
                    connection: $this->connection(),
                    name: $item->name,
                    columnTrackingEnabled: true,
                    primaryKeyName: $primaryKey,
                    columns: $columns
                ),
            ];
        });
    }

    public function setTableFilter(array $tableFilter)
    {
        $this->tableFilter = $tableFilter;

        return $this;
    }

    public function asCommand(Command $command): void
    {
        $tables = $this->handle();
        $headers = array_keys($tables->first()->toArray());
        $command->table(
            $headers,
            $tables->map(function (Table $table) {
                return $table->toArray();
            })->toArray(),
        );
        $command->info(count($tables).' tables have change tracking enabled');
    }
}
