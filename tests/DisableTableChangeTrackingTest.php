<?php

namespace Patabugen\MssqlChanges\Tests;

use Patabugen\MssqlChanges\Actions\DisableTableChangeTracking;
use Patabugen\MssqlChanges\Actions\EnableTableChangeTracking;
use Patabugen\MssqlChanges\Table;

class DisableTableChangeTrackingTest extends TestCase
{
    public function test_we_can_disable_a_single_table()
    {
        $table = Table::create('Contacts');
        // Be sure the table is already tracked.
        EnableTableChangeTracking::run($table);
        $this->assertEquals(
            'Change Tracking has been disabled for table Contacts',
            DisableTableChangeTracking::run($table)
        );

        $this->assertEquals(
            'Change Tracking was already disabled for table Contacts',
            DisableTableChangeTracking::run($table)
        );
    }

    public function test_we_can_disable_all_tables()
    {
        // These are the tables which exist in the test database
        $contacts = Table::create('Contacts');
        $addresses = Table::create('Addresses');
        $migrations = Table::create('migrations');

        $return = DisableTableChangeTracking::make()->runAllTables();
        $this->assertCount(3, $return);

        $this->assertEquals(
            'Change Tracking has been disabled for table Contacts',
            $return['Contacts']
        );

        $this->assertEquals(
            'Change Tracking has been disabled for table Addresses',
            $return['Addresses']
        );

        $this->assertEquals(
            'Change Tracking was already disabled for table migrations',
            $return['migrations']
        );
    }

    public function test_we_can_disable_a_table_from_artisan()
    {
        $result = $this->artisan('mssql:disable-table-change-tracking', ['table' => 'Contacts']);
        $result->expectsOutput('Change Tracking has been disabled for table Contacts');
    }

    public function test_we_can_disable_all_tables_from_artisan()
    {
        $this->artisan('mssql:disable-table-change-tracking', ['--all' => true])
            ->expectsOutput('Change Tracking has been disabled for table Addresses')
            ->expectsOutput('Change Tracking has been disabled for table Contacts')
            ->expectsOutput('Change Tracking was already disabled for table migrations');
    }
}
