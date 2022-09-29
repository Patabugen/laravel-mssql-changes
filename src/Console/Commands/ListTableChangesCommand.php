<?php

namespace Patabugen\MssqlChanges\Console\Commands;

use Illuminate\Console\Command;
use Patabugen\MssqlChanges\Actions\ListTableChanges;

class ListTableChangesCommand extends Command
{
    protected $signature = 'mssql:list-table-changes {table}';

    public function handle()
    {
        ListTableChanges::make()->asCommand($this);
    }
}
