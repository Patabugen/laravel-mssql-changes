<?php

namespace Patabugen\MssqlChanges\Tests;

use Patabugen\MssqlChanges\Actions\ListTableChanges;
use Patabugen\MssqlChanges\Change;
use Patabugen\MssqlChanges\Table;

class ListTableChangesTest extends TestCase
{
    public function test_a_table_reports_its_changes()
    {
        // Todo: Make a change to check it's there
        // Maybe get version, then pass version to ListTableChanges
        $table = Table::create('Contacts');
        $changes = ListTableChanges::run($table);

        $this->assertCount(1, $changes);
        $this->assertContainsOnlyInstancesOf(Change::class, $changes);
    }
}
