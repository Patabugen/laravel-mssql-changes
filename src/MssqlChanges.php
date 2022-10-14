<?php

namespace Patabugen\MssqlChanges;

use Illuminate\Support\Facades\DB;

class MssqlChanges
{
    private $dbConnection;

    private Database $database;

    public function __construct()
    {
        $this->dbConnection = DB::connection(config('mssql-changes.connection', 'default'));
        $this->database = Database::create($this->dbConnection);
    }
}
