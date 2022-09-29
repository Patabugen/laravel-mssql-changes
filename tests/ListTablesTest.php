<?php

namespace Patabugen\MssqlChanges\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Patabugen\MssqlChanges\Actions\ListTables;
use Patabugen\MssqlChanges\Database;
use Patabugen\MssqlChanges\Table;

class ListTablesTest extends TestCase
{
    public function test_we_can_list_tables_with_tracking_enabled()
    {
        // TODO: Create these databases/tables in the test.
        $tables = ListTables::run();
        $this->assertCount(135, $tables);
        $this->assertContainsOnlyInstancesOf(Table::class, $tables);
        $this->assertArrayHasKey('Contacts', $tables);
        $this->assertArrayHasKey('Transactions', $tables);
        $this->assertArrayHasKey('TransactionLines', $tables);
    }

    public function test_we_can_filter_and_list_tables_with_tracking_enabled()
    {
        $tables = ListTables::make()
            ->setTableFilter([ 'Contacts', 'TransactionLines' ])
            ->handle();

        $this->assertCount(2, $tables);
        $this->assertContainsOnlyInstancesOf(Table::class, $tables);
        $this->assertArrayHasKey('Contacts', $tables);
        $this->assertArrayNotHasKey('Transactions', $tables);
        $this->assertArrayHasKey('TransactionLines', $tables);
    }

    public function test_we_can_list_tables_from_artisan()
    {
        /**
         * Because we're not creating a test database we can't properly use
         * expectsTable. Hopefully I'll be able to add a test database (Rather
         * than testing against my dev one) - but in the mean time let's test a
         * few bits.
         */
        $command = $this->artisan('mssql:list-tables')
            ->assertSuccessful()
            ->expectsOutputToContain('135 tables have change tracking enabled')
            ->expectsOutputToContain('+---------------------------------------+------------------------+')
            ->expectsOutputToContain('| Name                                  | Column Tracking Status |')
            ->expectsOutputToContain('| Contacts                              | Enabled                |');
    }

    public function test_we_can_filter_tables_from_artisan()
    {
        $result = Artisan::call('mssql:list-tables --tables=Contacts');
        $this->assertEmpty($result);
    }
}
