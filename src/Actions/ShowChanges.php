<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Patabugen\MssqlChanges\Change;
use Patabugen\MssqlChanges\Table;
use Patabugen\MssqlChanges\Traits\HasVersionFiltersTrait;

class ShowChanges extends BaseAction
{
    use HasVersionFiltersTrait;

    public string $commandSignature = 'mssql:show-changes';

    public array $tableFilter = [];

    public function handle(): Collection
    {
        $tables = ListTables::make()
            ->onlyTrackingEnabled()
            ->setTableFilter($this->tableFilter)
            ->handle();

        $changes = $tables->flatMap(function (Table $table) {
            return ListTableChanges::make()
                ->fromVersion($this->fromVersion)
                ->toVersion($this->toVersion)
                ->handle($table);
        })->sortBy('sysChangeVersion');

        return $changes;
    }

    public function setTableFilter(array $tableFilter)
    {
        $this->tableFilter = $tableFilter;

        return $this;
    }

    public function asCommand(Command $command)
    {
        $this->readVersionFilters($command);
        $changes = $this->handle();
        if ($changes->isEmpty()) {
            $command->info('No changes found');

            return;
        }
        $headers = array_keys($changes->first()->toArray());
        $rows = $changes->map(function (Change $table) {
            return $table->toArray();
        })->toArray();

        $command->table($headers, $rows);
        $command->info(
            count($changes).' '.Str::plural('change', $changes).' found'
        );
    }
}
