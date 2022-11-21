<?php

namespace Patabugen\MssqlChanges\Tests;

use Patabugen\MssqlChanges\Actions\DisableTableChangeTracking;
use Patabugen\MssqlChanges\Actions\EnableTableChangeTracking;
use Patabugen\MssqlChanges\Table;

class EnableTableChangeTrackingTest extends TestCase
{
    public function test_we_can_enable_a_single_table()
    {
        $table = Table::create('Contacts');
        // Be sure the table is already tracked.
        DisableTableChangeTracking::run($table);
        $this->assertEquals(
            'Change Tracking enabled for table Contacts',
            EnableTableChangeTracking::run($table)
        );

        $this->assertEquals(
            'Change Tracking is already enabled for table Contacts',
            EnableTableChangeTracking::run($table)
        );
    }

    public function test_we_can_enable_all_tables()
    {
        // These are the tables which exist in the test database
        $contacts = Table::create('Contacts');
        $addresses = Table::create('Addresses');
        $migrations = Table::create('migrations');

        $return = EnableTableChangeTracking::make()->runAllTables();
        $this->assertCount(3, $return);

        $this->assertEquals(
            'Change Tracking is already enabled for table Contacts',
            $return['Contacts']
        );

        $this->assertEquals(
            'Change Tracking is already enabled for table Addresses',
            $return['Addresses']
        );

        $this->assertEquals(
            'Change Tracking enabled for table migrations',
            $return['migrations']
        );
    }

    public function test_we_can_enable_a_table_from_artisan()
    {
        $result = $this->artisan('mssql:enable-table-change-tracking', ['table' => 'Contacts']);
        $result->expectsOutput('Change Tracking is already enabled for table Contacts');
    }

    public function test_we_can_enable_all_tables_from_artisan()
    {
        $this->artisan('mssql:enable-table-change-tracking', ['--all' => true])
            ->expectsOutput('Change Tracking is already enabled for table Addresses')
            ->expectsOutput('Change Tracking is already enabled for table Contacts')
            ->expectsOutput('Change Tracking enabled for table migrations');
    }
}
