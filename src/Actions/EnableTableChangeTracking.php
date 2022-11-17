<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class EnableTableChangeTracking extends BaseAction
{
    public string $commandSignature = 'mssql:enable-table-change-tracking {table?} {--all}';

    private array $messages = [
        1 => 'Change Tracking enabled for table %s',
        2 => 'Change Tracking is already enabled for table %s',
    ];

    public function handle(string $rawTableName): string
    {
        // Some rudimentary sanitising/escaping.
        $tableName = Str::of($rawTableName)->ascii()->wrap('[', ']');

        throw_unless(
            $this->connection()->getSchemaBuilder()->hasTable($rawTableName),
            'Table '.$tableName.' does not exist'
            .' in database '.$this->connection()->getDatabaseName()
            .' at '.$this->connection()->getConfig('host')
        );

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
        $tables = [];
        if ($command->option('all')) {
            $tables = $this->connection()->select('SHOW TABLES')->get();
        } else {
            $tables = [$command->argument('table')];
        }
        ray($tables);
        dd();
        $command->info($this->handle(
            $command->argument('table')
        ));
    }
}
