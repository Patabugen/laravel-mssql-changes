<?php

namespace Patabugen\MssqlChanges\Commands;

use Illuminate\Console\Command;

class MssqlChangesCommand extends Command
{
    public $signature = 'mssql-changes';

    public $description = 'Does not do anything, yet';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
