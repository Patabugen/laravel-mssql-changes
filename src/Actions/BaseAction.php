<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Support\Facades\DB;

abstract class BaseAction
{
    protected function connection()
    {
        return DB::connection(config('mssql-changes.connection', null));
    }
}
