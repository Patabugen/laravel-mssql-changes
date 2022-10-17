<?php

namespace Patabugen\MssqlChanges\Console\Commands;

use Illuminate\Console\Command;
use Patabugen\MssqlChanges\Actions\DisableTableChangeTracking;

class DisableTableChangeTrackingCommand extends Command
{
    protected $signature = 'mssql:disable-table-change-tracking {table}';

    public function handle()
    {
        DisableTableChangeTracking::make()->asCommand($this);
    }
}
