<?php

namespace Patabugen\MssqlChanges\Console\Commands;

use Illuminate\Console\Command;
use Patabugen\MssqlChanges\Actions\EnableTableChangeTracking;

class EnableTableChangeTrackingCommand extends Command
{
    protected $signature = 'mssql:enable-table-change-tracking {table}';

    public function handle()
    {
        EnableTableChangeTracking::make()->asCommand($this);
    }
}
