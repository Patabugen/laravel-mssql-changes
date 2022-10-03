<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Patabugen\MssqlChanges\Database;

class EnableDatabaseChangeTracking extends BaseAction
{
    public string $commandSignature = 'mssql:enable-change-tracking';
    private array $messages = [
        1 => 'Change Tracking enabled for database %s',
        2 => 'Change Tracking is already enabled for database %s',
    ];

    public function handle(): string
    {
        $databaseName = Str::of($this->connection()->getDatabaseName())->ascii()->wrap('[', ']');
        try {
            $this->connection()->unprepared(
                'Alter Database '.$databaseName.' Set Change_Tracking = ON (Auto_CleanUP=ON, CHANGE_RETENTION=3 Days)',
            );
        } catch (QueryException $e) {
            if (Str::of($e->getMessage())->contains('Change tracking is already enabled for database')) {
                return $this->return($this->messages[2], $databaseName);
            } else {
                throw $e;
            }
        }
        return $this->return($this->messages[1], $databaseName);
    }

    private function return(string $messageTemplate, $databaseName): string
    {
        $message = sprintf($messageTemplate, $databaseName);
        logger($message);
        return $message;
    }

    public function asCommand(Command $command): void
    {
        $command->info($this->handle());

    }
}
