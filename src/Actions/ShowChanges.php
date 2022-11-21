<?php

namespace Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Patabugen\MssqlChanges\Change;
use Patabugen\MssqlChanges\Table;
use Patabugen\MssqlChanges\Traits\HasVersionFiltersTrait;
use Symfony\Component\Console\Helper\Table as ConsoleTable;
use Symfony\Component\Console\Helper\TableSeparator;

class ShowChanges extends BaseAction
{
    use HasVersionFiltersTrait;

    public string $commandSignature = 'mssql:show-changes';

    public array $tableFilter = [];

    public function handle(): Collection
    {
        $tables = ListTables::make()
            ->onlyWithTracking()
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
        $table = new ConsoleTable($command->getOutput());
        $table->setHeaders(array_keys($changes->first()->toArray()));
        // Turn the changes into an array (and word-wrap the Columns list)
        $changes = $changes->map(function (Change $tableChanges) {
            $row = $tableChanges->toArray();
            $row['Columns Changed'] = wordwrap(
                $row['Columns Changed'],
                config('mssql-changes.columns-changed-max-width')
            );

            return $row;
        });
        // Add a separator between each row to make it easier to read
        $rows = [];
        foreach ($changes as $change) {
            $rows[] = $change;
            $rows[] = new TableSeparator;
        }
        // Remove the duplicate sepratator at the end
        array_pop($rows);

        // Set the rows.
        $table->setRows($rows);
        $table->render();
        $command->info(
            count($changes).' '.Str::plural('change', $changes).' found'
        );
    }
}
