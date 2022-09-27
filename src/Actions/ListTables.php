<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ListTables extends BaseAction
{
    public string $commandSignature = 'mssql:list-tables';

    public function handle(): Collection
    {
        $tables = $this->connection()
            ->table('sys.change_tracking_tables')
            ->select('sys.tables.name As TableName')
            ->join('sys.tables', 'sys.change_tracking_tables.object_id', 'sys.tables.object_id')
            ->get()
            ->pluck('TableName');

        return $tables;
    }

    public function asCommand(Command $command): void
    {
        $changes = $this->handle();
        ray($changes);
    }
}
