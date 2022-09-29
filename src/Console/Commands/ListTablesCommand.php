<?php

namespace Patabugen\MssqlChanges\Console\Commands;

use Illuminate\Console\Command;
use Patabugen\MssqlChanges\Actions\ListTables;

class ListTablesCommand extends Command
{
    protected $signature = 'mssql:list-tables';

    public function handle()
    {
        ListTables::make()->asCommand($this);
    }
}
