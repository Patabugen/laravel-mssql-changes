<?php

namespace  Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Patabugen\MssqlChanges\Table;

class ListTableChanges extends BaseAction
{
    public function handle(Table $tables): Collection
    {
        $changes = new Collection;

        return $changes;
    }
}
