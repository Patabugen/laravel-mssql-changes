<?php

namespace Patabugen\MssqlChanges;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Patabugen\MssqlChanges\Actions\ListTables;

class Table
{
    public ConnectionInterface $connection;

    public Collection $changes;

    public string $name;

    public bool $columnTrackingEnabled;

    public function __construct(
        ConnectionInterface $connection,
        string $name,
        bool $columnTrackingEnabled,
    ) {
        $this->connection = $connection;
        $this->name = $name;
        $this->columnTrackingEnabled = $columnTrackingEnabled;
    }

    public function getPrimaryKeyColumn()
    {
        return 'ContactID';
    }

    public function toArray()
    {
        return [
            'Name' => $this->name,
            'Column Tracking Status' => $this->columnTrackingEnabled ? 'Enabled' : 'Disabled',
        ];
    }

    public static function create(string $name)
    {
        $foundTables = ListTables::make()->setTableFilter([$name])->handle();
        throw_if(
            $foundTables->isEmpty(),
            "A table named '{$name}' with tracking enabled was not found"
        );
        throw_if(
            $foundTables->count() > 1,
            "More than one table was found matching '{$name}'"
        );

        return $foundTables->first();
    }
}
