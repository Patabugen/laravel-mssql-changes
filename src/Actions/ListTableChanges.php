<?php

namespace  Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Patabugen\MssqlChanges\Change;
use Patabugen\MssqlChanges\Table;
use Patabugen\MssqlChanges\Traits\HasVersionFiltersTrait;

class ListTableChanges extends BaseAction
{
    use HasVersionFiltersTrait;

    public function handle(Table $table): Collection
    {
        $sql = $this->connection()
            ->table('TABLE-PLACEHOLDER', 'CT')
            ->select('CT.*')
            ->whereNotNull('CT.SYS_CHANGE_VERSION')
            ->orderBy('CT.SYS_CHANGE_VERSION', 'ASC');

        foreach ($table->columns as $column) {
            $sql->selectRaw(
                "CHANGE_TRACKING_IS_COLUMN_IN_MASK(COLUMNPROPERTY(OBJECT_ID('"
                .$table->name
                ."'), '"
                .$column->COLUMN_NAME
                ."','ColumnId'), CT.SYS_CHANGE_COLUMNS) as '"
                .$column->COLUMN_NAME
                ."Changed'");
        }
        $sql = Str::of($sql->toSql())->replace(
            '[TABLE-PLACEHOLDER]',
            'CHANGETABLE(CHANGES '.$table->name.', '.($this->fromVersion - 1).')' // Minus 1 for >=
        );

        $changes = collect($this->connection()->select($sql))->map(function (\stdClass $item) use ($table) {
            if (isset($this->toVersion) && $item->SYS_CHANGE_VERSION > $this->toVersion) {
                return false;
            }
            $changedColumns = new Collection;
            foreach ($table->columns as $column) {
                $property = $column->COLUMN_NAME.'Changed';
                if ($item->$property) {
                    $changedColumns->push($column->COLUMN_NAME);
                }
            }

            // Support composite primary keys
            $primaryKey = Str::of($table->primaryKeyName)->explode(',')->map(fn ($key) => $item->$key)->join(',');

            return new Change(
                connection: $this->connection(),
                primaryKey: $primaryKey,
                table: $table,
                columnName: $changedColumns->join(', '),
                sysChangeVersion: $item->SYS_CHANGE_VERSION,
            );
        })->filter();

        return $changes;
    }

    public function asCommand(Command $command)
    {
        $table = Table::create($command->argument('table'));
        $this->readVersionFilters($command);
        $changes = $this->handle($table);

        if ($changes->isEmpty()) {
            $command->info('No changes found in '.$table->name);

            return;
        }
        $headers = array_keys($changes->first()->toArray());
        $rows = $changes->map(function (Change $table) {
            return $table->toArray();
        })->toArray();

        $command->table(
            $headers,
            $rows,
        );

        $command->info('Table '.$table->fullName().' has '.count($changes).' changes');
    }
}
