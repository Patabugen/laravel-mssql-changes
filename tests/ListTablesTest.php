<?php

namespace Patabugen\MssqlChanges\Tests;

use Patabugen\MssqlChanges\Actions\DisableTableChangeTracking;
use Patabugen\MssqlChanges\Actions\EnableTableChangeTracking;
use Patabugen\MssqlChanges\Actions\ListTables;
use Patabugen\MssqlChanges\Table;

class ListTablesTest extends TestCase
{
    public function test_we_can_list_only_tables_with_tracking_enabled()
    {
        // Our test starts with 3 tables, Contacts and Addresses have tracking enabled
        // while migrations does not.
        DisableTableChangeTracking::run(Table::create('Contacts'));
        $tables = ListTables::make()->onlyWithTracking()->handle();
        $this->assertCount(1, $tables);
        $this->assertContainsOnlyInstancesOf(Table::class, $tables);

        $this->assertArrayHasKey('Addresses', $tables);
        $this->assertArrayNotHasKey('migrations', $tables);
        $this->assertArrayNotHasKey('Contacts', $tables);

        // Reenable tracking on Contacts and see all three tables
        EnableTableChangeTracking::run(Table::create('Contacts'));
        $tables = ListTables::run();
        $this->assertCount(3, $tables);
        $this->assertContainsOnlyInstancesOf(Table::class, $tables);
        $this->assertArrayHasKey('Addresses', $tables);
        $this->assertArrayHasKey('Contacts', $tables);
        $this->assertArrayHasKey('migrations', $tables);
    }

    public function test_we_can_filter_list_tables()
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
                'Name', 'Primary Key', 'Column Tracking Status',
            ], [
                ['Addresses', 'AddressID', 'Enabled'],
                ['Contacts', 'ContactID', 'Enabled'],
                ['migrations', 'id', 'Disabled'],
            ])
            ->expectsOutputToContain('2 tables have change tracking enabled')
            ->expectsOutputToContain('1 tables have change tracking disabled');

        DisableTableChangeTracking::run(Table::create('Contacts'));
        $this->artisan('mssql:list-tables')
            ->expectsTable([
                'Name', 'Primary Key', 'Column Tracking Status',
            ], [
                ['Addresses', 'AddressID', 'Enabled'],
                ['Contacts', 'ContactID', 'Disabled'],
                ['migrations', 'id', 'Disabled'],
            ])
            ->expectsOutputToContain('1 tables have change tracking enabled')
            ->expectsOutputToContain('2 tables have change tracking disabled');
    }
}
