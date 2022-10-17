<?php

namespace Patabugen\MssqlChanges\Tests;

use Patabugen\MssqlChanges\Actions\DisableTableChangeTracking;
use Patabugen\MssqlChanges\Actions\EnableTableChangeTracking;
use Patabugen\MssqlChanges\Actions\ListTables;
use Patabugen\MssqlChanges\Table;

class ListTablesTest extends TestCase
{
    public function test_we_can_list_tables_with_tracking_enabled()
    {
        DisableTableChangeTracking::run('Contacts');
        $tables = ListTables::run();
        $this->assertCount(1, $tables);
        $this->assertContainsOnlyInstancesOf(Table::class, $tables);
        $this->assertArrayNotHasKey('Contacts', $tables);
        $this->assertArrayHasKey('Addresses', $tables);

        EnableTableChangeTracking::run('Contacts');
        $tables = ListTables::run();
        $this->assertCount(2, $tables);
        $this->assertContainsOnlyInstancesOf(Table::class, $tables);
        $this->assertArrayHasKey('Contacts', $tables);
        $this->assertArrayHasKey('Addresses', $tables);
    }

    public function test_we_can_filter_and_list_tables_with_tracking_enabled()
    {
        $tables = ListTables::make()
            ->setTableFilter(['Contacts'])
            ->handle();

        $this->assertCount(1, $tables);
        $this->assertContainsOnlyInstancesOf(Table::class, $tables);
        $this->assertArrayHasKey('Contacts', $tables);
        $this->assertArrayNotHasKey('Addresses', $tables);
    }

    public function test_we_can_list_tables_from_artisan()
    {
        $this->artisan('mssql:list-tables')
            ->expectsTable([
                'Name', 'Column Tracking Status',
            ], [
                ['Addresses', 'Enabled'],
                ['Contacts', 'Enabled'],
            ])
            ->expectsOutputToContain('2 tables have change tracking enabled');

        DisableTableChangeTracking::run('Contacts');
        $this->artisan('mssql:list-tables')
            ->expectsTable([
                'Name', 'Column Tracking Status',
            ], [
                ['Addresses', 'Enabled'],
            ])
            ->expectsOutputToContain('1 tables have change tracking enabled');
    }
}
