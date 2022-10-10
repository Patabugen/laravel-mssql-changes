<?php

namespace Patabugen\MssqlChanges;

use Illuminate\Database\ConnectionInterface;

class Change
{
    public ConnectionInterface $connection;

    public string $primaryKey;

    public string $columnName;

    public Table $table;

    public int $sysChangeVersion;

    public function __construct(
        ConnectionInterface $connection,
        Table $table,
        string|int $primaryKey,
        string $columnName,
        string $sysChangeVersion,
    ) {
        $this->connection = $connection;
        $this->primaryKey = $primaryKey;
        $this->table = $table;
        $this->columnName = $columnName;
        $this->sysChangeVersion = $sysChangeVersion;
    }

    public function toArray()
    {
        return [
            'Table' => $this->table->name,
            'Primary Key' => $this->primaryKey,
            'Columns Changed' => $this->columnName,
            'Change Version' => $this->sysChangeVersion,
        ];
    }
}
