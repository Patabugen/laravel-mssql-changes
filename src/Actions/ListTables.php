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
     * @return Collection
     */
    public function handle(): Collection
    {
        $query = $this->connection()
            ->table('sys.change_tracking_tables')
            ->select('*')
            ->join('sys.tables', 'sys.change_tracking_tables.object_id', 'sys.tables.object_id')
            ->when(!empty($this->tableFilter), fn ($query) => $query->whereIn('sys.tables.name', $this->tableFilter));

        return $query->get()->mapWithKeys(function($item){
            return [
                $item->name => new Table(
                    $this->connection(),
                    $item->name,
                    true,
                )
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
        $changes = $this->handle();
        ray($changes);
    }
}
