<?php

namespace Patabugen\MssqlChanges\Tests;

use Patabugen\MssqlChanges\Actions\GetVersion;
use Patabugen\MssqlChanges\Actions\ListTableChanges;
use Patabugen\MssqlChanges\Actions\ShowChanges;
use Patabugen\MssqlChanges\Change;
use Patabugen\MssqlChanges\Models\Address;
use Patabugen\MssqlChanges\Models\Contact;
use Patabugen\MssqlChanges\Table;

class ShowChangesTest extends TestCase
{
    public function test_show_changes_includes_multiple_tables()
    {
        $contactTable = Table::create('Contacts');
        $addressTable = Table::create('Addresses');

        Contact::factory()->create();
        Address::factory()->create();
        Address::factory()->create();

        $changes = ShowChanges::run();
        $this->assertCount(3, $changes);
        $this->assertContainsOnlyInstancesOf(Change::class, $changes);
        $this->assertCount(1, $changes->where('table', $contactTable));
        $this->assertCount(2, $changes->where('table', $addressTable));
    }

    public function test_we_can_show_all_changes_from_artisan()
    {
        // To keep things simpler if we add fields later, we will create
        // the objects but then use the version filter to only look
        // at a specific column change.
        $contact1 = Contact::factory()->create();
        $address1 = Address::factory()->create();
        $address2 = Address::factory()->create();

        $contact1->update([ 'Firstname' => fake()->firstName()]);
        $version = GetVersion::run(); // Get the version we reached when we made $contact1
        $address1->update([ 'first_line' => fake()->address()]);
        $address2->update([ 'first_line' => fake()->address()]);

        $headers = [ 'Table', 'Primary Key', 'Columns Changed', 'Change Version' ];
        $rows = [
            [ 'Contacts', '1', 'Firstname', $version ],
            [ 'Addresses', '1', 'first_line', $version + 1 ],
            [ 'Addresses', '2', 'first_line', $version + 2 ],
        ];

        $this->artisan('mssql:show-changes', ['--from' => $version])
            ->assertSuccessful()
            ->expectsTable($headers, $rows)
            ->expectsOutputToContain('3 changes found');
    }
}
