<?php

namespace Patabugen\MssqlChanges\Commands;

use Illuminate\Console\Command;

class MssqlChangesCommand extends Command
{
    public $signature = 'mssql:show-changes';

    public $description = 'Show changes from any tables with Change Tracking in MSSQL';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
