<?php

namespace Patabugen\MssqlChanges\Tests;

use Patabugen\MssqlChanges\Actions\ListTableChanges;
use Patabugen\MssqlChanges\Change;
use Patabugen\MssqlChanges\Table;

class ShowChangesTest extends TestCase
{
    public function test_show_changes_includes_multiple_tables()
    {
        // Todo: Make a change to check it's there
        // Maybe get version, then pass version to ListTableChanges
        $table = Table::create('Contacts');
        $changes = ListTableChanges::run($table);

        $this->assertCount(1, $changes);
        $this->assertContainsOnlyInstancesOf(Change::class, $changes);
        // Assert some of the changes are from one table, and the others from another.
        $this->markTestIncomplete();
    }

    public function test_we_can_list_table_changes_from_artisan()
    {
        /**
         * Because we're not creating a test database we can't properly use
         * expectsTable. Hopefully I'll be able to add a test database (Rather
         * than testing against my dev one) - but in the mean time let's test a
         * few bits.
         */
        $this->artisan('mssql:list-table-changes Contacts')
            ->assertSuccessful()
            ->expectsOutputToContain('| Table    | Primary Key | Columns Changed      | Change Version |')
            ->expectsOutputToContain('Table Contacts has 1 changes');
    }
}
