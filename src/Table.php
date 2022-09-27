<?php

namespace Patabugen\MssqlChanges;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

}
