<?php

namespace Patabugen\MssqlChanges\Commands;

use Illuminate\Console\Command;

class ListChanges extends BaseCommand
{
    public $signature = 'mssql:list-changes';

    public $description = 'List changes from any tables with Change Tracking in MSSQL';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
