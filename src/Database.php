<?php

namespace Patabugen\MssqlChanges;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Database implements Arrayable
{
    public ConnectionInterface $connection;

    public string $name;

    public int $retentionPeriodUnits;

    public string $retentionPeriodUnitsDesc;

    public function __construct(
        ConnectionInterface $connection,
        string $name,
        int $retentionPeriodUnits,
        string $retentionPeriodUnitsDesc
    ) {
        $this->connection = $connection;
        $this->name = $name;
        $this->retentionPeriodUnits = $retentionPeriodUnits;
        $this->retentionPeriodUnitsDesc = $retentionPeriodUnitsDesc;
    }

    public function toArray()
    {
        return [
            'Name' => $this->name,
            'Retention Period' => $this->retentionPeriodUnits
                    .' '
                    .Str::of($this->retentionPeriodUnitsDesc)->lower()->title(),
        ];
    }

    public static function create()
    {
        $connection = DB::connection(config('database.default'));

        $details = $connection->table('sys.change_tracking_databases')
            ->selectRaw('DB_NAME(database_id) AS name, retention_period_units, retention_period_units_desc')
            ->first();

        return new Database(
            $connection, $details->name, $details->retention_period_units, $details->retention_period_units_desc
        );
    }
}
