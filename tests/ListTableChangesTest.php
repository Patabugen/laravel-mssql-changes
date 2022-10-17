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

        // We're going to test just changing a single field - so first let's create
        // the contact.
        $contact = Contact::create();

        // Then we'll make a single column change and use GetVersion to filter
        // out the previous ones.
        $contact->Firstname = fake()->firstName;
        $contact->save();

        $version = GetVersion::run();

        $headers = [ 'Table', 'Primary Key', 'Columns Changed', 'Change Version' ];
        $rows = [
            [ 'Contacts', '1', 'Firstname', $version ]
        ];

        $this->artisan('mssql:list-table-changes', ['table' => 'Contacts', '--from' => $version])
            ->assertSuccessful()
            ->expectsTable($headers, $rows)
            ->expectsOutputToContain('Table '.$table->fullName().' has 1 change');
    }
}
