<?php

namespace Patabugen\MssqlChanges;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class Database implements Arrayable
{
    public string $name;

    public int $retentionPeriodUnits;

    public string $retentionPeriodUnitsDesc;

    public function __construct(
        string $name,
        int $retentionPeriodUnits,
        string $retentionPeriodUnitsDesc
    ) {
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
}
