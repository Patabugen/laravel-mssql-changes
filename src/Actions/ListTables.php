<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Patabugen\MssqlChanges\Table;

class ListTables extends BaseAction
{
    public array $tableFilter = [];

    public bool $onlyTrackingEnabled = false;

    /**
     * Returns a collection of Table objects for any table which has Change Tracking
     * enabled. Does not include tables with tracking disabled.
     */
    public function handle(): Collection
    {
        $query = $this->connection()
            ->table('sys.tables')
            ->select(['sys.tables.*', 'sys.change_tracking_tables.is_track_columns_updated_on'])
            // Link the change-tracking system info to the tables list
            ->leftJoin('sys.change_tracking_tables', 'sys.tables.object_id', 'sys.change_tracking_tables.object_id')
            ->orderBy('sys.tables.name')
            ->when($this->onlyTrackingEnabled, fn ($query) => $query->where('sys.change_tracking_tables.is_track_columns_updated_on', '=', '1'))
            ->when(! empty($this->tableFilter), fn ($query) => $query->whereIn('sys.tables.name', $this->tableFilter));

        return $query->get()->mapWithKeys(function ($item) {
            $primaryKey = collect($this->connection()->select('EXEC sp_pkeys ?', [$item->name]));

            $primaryKey = ($primaryKey->isEmpty())
                ? ''
                : $primaryKey->pluck('COLUMN_NAME')->join(',');

            $changeTrackingEnabled = $item->is_track_columns_updated_on == '1';

            $columns = $this->connection()
                ->table('INFORMATION_SCHEMA.COLUMNS')
                ->select('COLUMN_NAME')
                ->where('TABLE_NAME', $item->name)
                ->get();

            return [
                $item->name => new Table(
                    connection: $this->connection(),
                    name: $item->name,
                    columnTrackingEnabled: $changeTrackingEnabled,
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

    public function onlyWithTracking($onlyTrackingEnabled = true)
    {
        $this->onlyTrackingEnabled = $onlyTrackingEnabled;

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
        $stats = $tables->countBy('columnTrackingEnabled');
        $command->info($stats[0].' tables have change tracking disabled');
        $command->info($stats[1].' tables have change tracking enabled');
    }
}
