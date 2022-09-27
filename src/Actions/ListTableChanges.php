<?php

namespace  Patabugen\MssqlChanges\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Patabugen\MssqlChanges\Change;
use Patabugen\MssqlChanges\Table;

class ListTableChanges extends BaseAction
{
    public function handle(Table $table): Collection
    {
        $changes = new Collection;

        $primaryKeyColumn = 'ContactID';

        $sql = Str::of($this->connection()
            ->table($table->name, 'c')
            ->select(['*'])
            ->crossJoin('CROSS-APPLY-PLACEHOLDER')
            ->whereNotNull('CT.SYS_CHANGE_VERSION')
            ->orderBy('CT.SYS_CHANGE_VERSION')
            ->toSql());

        $sql = $sql->replace(
            'cross join [CROSS-APPLY-PLACEHOLDER] where',
            'CROSS APPLY CHANGETABLE(VERSION Contacts, (ContactID), (c.ContactID)) AS CT where'
        );

        $changes = collect($this->connection()->select($sql))->map(function (\stdClass $item) use ($table) {
            return new Change(
                connection: $this->connection(),
                primaryKey: $table->getPrimaryKeyColumn(),
                table: $table,
                columnName: 'unknown',
            );
        });
        ray($changes);

        return $changes;
    }
}
