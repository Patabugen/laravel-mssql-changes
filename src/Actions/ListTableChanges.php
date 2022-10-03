<?php

namespace  Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Patabugen\MssqlChanges\Change;
use Patabugen\MssqlChanges\Table;

class ListTableChanges extends BaseAction
{
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
            'CHANGETABLE(CHANGES '.$table->name.', 0)'
        );

        $changes = collect($this->connection()->select($sql))->map(function (\stdClass $item) use ($table) {
            $changedColumns = new Collection;
            foreach ($table->columns as $column) {
                $property = $column->COLUMN_NAME.'Changed';
                if ($item->$property) {
                    $changedColumns->push($column->COLUMN_NAME);
                }
            }

            return new Change(
                connection: $this->connection(),
                primaryKey: $item->{$table->primaryKeyName},
                table: $table,
                columnName: $changedColumns->join(', '),
                sysChangeVersion: $item->SYS_CHANGE_VERSION,
            );
        });

        return $changes;
    }

    public function asCommand(Command $command)
    {
        $table = Table::create($command->argument('table'));

        $changes = $this->handle($table);

        if ($changes->isEmpty()) {
            $command->info('No changes found in '.$table->name);
            return;
        }
        $headers = array_keys($changes->first()->toArray());
        $command->table(
            $headers,
            $changes->map(function (Change $table) {
                return $table->toArray();
            })->toArray(),
        );
        $command->info('Table '.$table->name.' has '.count($changes).' changes');
    }
}
