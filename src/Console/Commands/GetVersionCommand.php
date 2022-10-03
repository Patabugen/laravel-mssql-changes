<?php

namespace Patabugen\MssqlChanges\Console\Commands;

use Illuminate\Console\Command;
use Patabugen\MssqlChanges\Actions\GetVersion;

class GetVersionCommand extends Command
{
    protected $signature = 'mssql:get-version';

    public function handle()
    {
        GetVersion::make()->asCommand($this);
    }
}
