<?php

namespace  Patabugen\MssqlChanges\Actions;

use Illuminate\Console\Command;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Patabugen\MssqlChanges\Change;
use Patabugen\MssqlChanges\Table;

class ListTableChanges extends BaseAction
{
    public function handle(Table $table): Collection
    {
        $sql = $this->connection()
            ->table($table->name, 'c')
            ->select([ '*' ])
            ->crossJoin('CROSS-APPLY-PLACEHOLDER')
            ->whereNotNull('CT.SYS_CHANGE_VERSION')
            ->orderBy('CT.SYS_CHANGE_VERSION');

//
//        --CHANGE_TRACKING_IS_COLUMN_IN_MASK to interprets the SYS_CHANGE_COLUMNS value that is returned by the CHANGETABLE(CHANGES …) function.
// SELECT *
//   ,COLUMNPROPERTY(OBJECT_ID('SalesLT.Customer'), 'SalesPerson', 'ColumnId') as 'ColumnId'
//    ,CHANGE_TRACKING_IS_COLUMN_IN_MASK(COLUMNPROPERTY(OBJECT_ID('Contacts'), 'Forenames', 'ColumnId'), CT.SYS_CHANGE_COLUMNS) as 'Forenames Changed'
//    ,CHANGE_TRACKING_IS_COLUMN_IN_MASK(COLUMNPROPERTY(OBJECT_ID('Contacts'), 'Surname', 'ColumnId'), CT.SYS_CHANGE_COLUMNS) as 'Surname Changed'
//
//FROM CHANGETABLE (CHANGES Contacts, 0) AS CT
//    -- WHERE CHANGE_TRACKING_IS_COLUMN_IN_MASK(COLUMNPROPERTY(OBJECT_ID('SalesLT.Customer'), 'SalesPerson', 'ColumnId'), CT.SYS_CHANGE_COLUMNS) = 1

        $sql = Str::of($sql)->replace(
            'cross join [CROSS-APPLY-PLACEHOLDER] where',
            'CROSS APPLY CHANGETABLE(VERSION Contacts, (ContactID), (c.ContactID)) AS CT where'
        );

        $changes = collect($this->connection()->select($sql))->map(function (\stdClass $item) use ($table) {
            return new Change(
                connection: $this->connection(),
                primaryKey: $table->primaryKeyName,
                table: $table,
                columnName: "unknown",
                sysChangeVersion: $item->SYS_CHANGE_VERSION,
            );
        });
        return $changes;
    }

    public function asCommand (Command $command)
    {
        $table = Table::create($command->argument('table'));

        $changes = $this->handle($table);
        $headers = array_keys($changes->first()->toArray());
        $command->table(
            $headers,
            $changes->map(function(Change $table){
                return $table->toArray();
            })->toArray(),
        );
        $command->info('Table '. $table->name.' has '. count($changes).' changes');

    }
}
