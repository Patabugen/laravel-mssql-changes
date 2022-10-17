<?php

namespace Patabugen\MssqlChanges\Tests;

use Patabugen\MssqlChanges\Actions\GetVersion;
use Patabugen\MssqlChanges\Actions\ListTableChanges;
use Patabugen\MssqlChanges\Change;
use Patabugen\MssqlChanges\Models\Contact;
use Patabugen\MssqlChanges\Table;

class ListTableChangesTest extends TestCase
{
    public function test_a_table_reports_its_changes()
    {
        $table = Table::create('Contacts');
        $changes = ListTableChanges::run($table);
        $this->assertEmpty($changes);

        $contact = Contact::create();
        $changes = ListTableChanges::run($table);
        $this->assertCount(1, $changes);
        $this->assertContainsOnlyInstancesOf(Change::class, $changes);

        /** @var Change $change */
        $change = $changes->first();
        $this->assertEquals('ContactID, Firstname, Surname', $change->columnName);
        $this->assertEquals('1', $change->primaryKey);
        $this->assertEquals('Contacts', $change->table->name);
    }

    public function test_we_can_list_table_changes_from_artisan()
    {
        $table = Table::create('Contacts');
        $changes = ListTableChanges::run($table);
        $this->assertEmpty($changes);

        $contact = Contact::create();
        $version = GetVersion::run();

        // Use the version to filter out the create, and only show the
        // update below.
        $changes = ListTableChanges::make()
            ->fromVersion($version)
            ->handle($table);

        $contact->Firstname = fake()->firstName;
        $contact->save();
        $this->withoutMockingConsoleOutput();
        $command = $this->artisan('mssql:list-table-changes Contacts');
        ray($command);
        // ->assertSuccessful();

        $command->expectsTable(
                [ 'Table', 'Primary Key', 'Columns Changed', 'Change Version' ],
                [
                    [ 'Contacts', '1', 'Firstname', $version + 1 ]
                ]
            )
            ->expectsOutputToContain('Table Contacts has 1 changes');
    }
}
