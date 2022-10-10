<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Patabugen\MssqlChanges\Database;
use Patabugen\MssqlChanges\Table;

class EnableTableChangeTracking extends BaseAction
{
    public string $commandSignature = 'mssql:enable-table-change-tracking {table}';

    private array $messages = [
        1 => 'Change Tracking enabled for table %s',
        2 => 'Change Tracking is already enabled for table %s',
    ];

    public function handle(string $tableName): string
    {
        // Some rudimentary sanitising/escaping.
        $tableName = Str::of($tableName)->ascii()->wrap('[', ']');
        try {
            $this->connection()->unprepared(
                'ALTER TABLE '.$tableName.' ENABLE CHANGE_TRACKING WITH (TRACK_COLUMNS_UPDATED = ON);',
            );
        } catch (QueryException $e) {
            if (Str::of($e->getMessage())->contains('Change tracking is already enabled for table')) {
                return $this->return($this->messages[2], $tableName);
            } else {
                throw $e;
            }
        }
        return $this->return($this->messages[1], $tableName);
    }

    private function return(string $messageTemplate, $databaseName): string
    {
        $message = sprintf($messageTemplate, $databaseName);
        logger($message);
        return $message;
    }

    public function asCommand(Command $command): void
    {
        $command->info($this->handle(
            $command->argument('table')
        ));

    }
}
