<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

abstract class BaseAction
{
    use AsAction;

    protected function connection()
    {
        return DB::connection(config('mssql-changes.connection', null));
    }
}
