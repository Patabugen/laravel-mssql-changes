<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Patabugen\MssqlChanges\Table;

class EnableTableChangeTracking extends BaseAction
{
    private array $messages = [
        1 => 'Change Tracking enabled for table %s',
        2 => 'Change Tracking is already enabled for table %s',
    ];

    public function handle(Table $table): string
    {
        $tableName = Str::of($table->name)->ascii()->wrap('[', ']');
        try {
            $this->connection()->unprepared(
                'ALTER TABLE '.$tableName.' ENABLE CHANGE_TRACKING WITH (TRACK_COLUMNS_UPDATED = ON);',
            );
        } catch (QueryException $e) {
            if (Str::of($e->getMessage())->contains('Change tracking is already enabled for table')) {
                return $this->return($this->messages[2], $table->name);
            } else {
                throw $e;
            }
        }

        return $this->return($this->messages[1], $table->name);
    }

    private function return(string $messageTemplate, $databaseName): string
    {
        $message = sprintf($messageTemplate, $databaseName);
        logger($message);

        return $message;
    }

    public function runAllTables()
    {
        $tables = ListTables::run();
        return $tables->map(function(Table $table){
            return $this->handle($table);
        });
    }

    public function asCommand(Command $command): void
    {
        $tables = [];
        if ($command->option('all')) {
            $tables = ListTables::run();
        } else {
            $tables = collect([
                Table::create($command->argument('table')),
            ]);
        }
        $tables->each(function (Table $table) use ($command) {
            if (empty($table->primaryKeyName)) {
                $command->warn($table->name.' cannot have Change Tracking enabled because it does not have a Primary Key');

                return;
            }ray($table);
            $command->info($this->handle($table));
        });
    }
}
