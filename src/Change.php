<?php

namespace Patabugen\MssqlChanges;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Patabugen\MssqlChanges\Actions\ListTables;

class Change
{
    private ConnectionInterface $connection;
    private string $primaryKey;
    private string $columnName;
    private Table $table;

    public function __construct(
        ConnectionInterface $connection,
        Table $table,
        string|int $primaryKey,
        string $columnName,
    ) {
        $this->connection = $connection;
        $this->primaryKey = $primaryKey;
        $this->table = $table;
        $this->columnName = $columnName;
    }

    public function toArray()
    {
        return [
            'Table' => $this->table->name,
            'Primary Key' => $this->primaryKey,
            'Column Name' => $this->columnName
        ];
    }
}
