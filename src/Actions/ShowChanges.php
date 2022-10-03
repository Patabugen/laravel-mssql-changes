<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Patabugen\MssqlChanges\Change;
use Patabugen\MssqlChanges\Table;

class ShowChanges extends BaseAction
{
    public string $commandSignature = 'mssql:show-changes';

    public array $tableFilter = [];

    public function handle(): Collection
    {
        $tables = ListTables::make()
            ->setTableFilter($this->tableFilter)
            ->handle();

        $changes = $tables->flatMap(function (Table $table){
            return ListTableChanges::run($table);
        });
        return $changes;
    }

    public function setTableFilter(array $tableFilter)
    {
        $this->tableFilter = $tableFilter;

        return $this;
    }

    public function asCommand(Command $command)
    {
//        $table = Table::create($command->argument('table'));

        $changes = $this->handle();
        if ($changes->isEmpty()) {
            $command->info('No changes found');
            return;
        }
        $headers = array_keys($changes->first()->toArray());
        $command->table(
            $headers,
            $changes->map(function (Change $table) {
                return $table->toArray();
            })->toArray(),
        );
        $command->info(
            count($changes).' '.Str::plural('change', $changes).' found'
        );
    }
}
