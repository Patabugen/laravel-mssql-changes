<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Patabugen\MssqlChanges\Table;

class DisableTableChangeTracking extends BaseAction
{
    private array $messages = [
        1 => 'Change Tracking has been disabled for table %s',
        2 => 'Change Tracking was already disabled for table %s',
    ];

    public function handle(Table $table): string
    {
        // Some rudimentary sanitising/escaping.
        $tableName = Str::of($table->name)->ascii()->wrap('[', ']');

        try {
            $this->connection()->unprepared(
                'ALTER TABLE '.$tableName.' DISABLE CHANGE_TRACKING',
            );
        } catch (QueryException $e) {
            if (Str::of($e->getMessage())->contains('Change tracking is not enabled on table')) {
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
            }
            $command->info($this->handle($table));
        });
    }
}
