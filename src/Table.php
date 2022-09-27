<?php

namespace Patabugen\MssqlChanges;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Patabugen\MssqlChanges\Actions\ListTables;

class Table
{
    private Collection $changes;

    public string $name;

    private Collection $tables;

    public bool $columnTrackingEnabled;

    public string $retentionPeriodUnitsDesc;

    public function __construct(
        ConnectionInterface $connection,
        string $name,
        bool $columnTrackingEnabled,
    ) {
        $this->connection = $connection;
        $this->name = $name;
        $this->columnTrackingEnabled = $columnTrackingEnabled;
    }

    public function toArray()
    {
        return [
            'Name' => $this->name,
            'Column Tracking Status' => $this->columnTrackingEnabled ? 'Enabled' : 'Disabled'
        ];
    }

    static function create(string $name)
    {
        $foundTables = ListTables::make()->setTableFilter([$name])->handle();
        throw_if (
            $foundTables->isEmpty(),
            "A table named '{$name}' with tracking enabled was not found"
        );
        throw_if (
            $foundTables->count() > 1,
            "More than one table was found matching '{$name}'"
        );
        return $foundTables->first();
    }
}
