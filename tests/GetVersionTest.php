<?php

namespace Patabugen\MssqlChanges\Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Patabugen\MssqlChanges\Actions\GetVersion;
use Patabugen\MssqlChanges\Actions\ListTableChanges;
use Patabugen\MssqlChanges\Change;
use Patabugen\MssqlChanges\Models\Contact;
use Patabugen\MssqlChanges\Table;

class GetVersionTest extends TestCase
{
    // use LazilyRefreshDatabase;

    public function test_get_version_reports_correct_version()
    {
        $version = GetVersion::run();

        $contact = Contact::factory()->create();

        // Maybe get version, then pass version to ListTableChanges
        $table = Table::create('Contacts');
        $changes = ListTableChanges::run($table);

        $this->assertCount(1, $changes);
        $this->assertContainsOnlyInstancesOf(Change::class, $changes);
        // Assert some of the changes are from one table, and the others from another.
        $this->markTestIncomplete();
    }

    public function test_we_can_get_version_from_artisan()
    {
        /**
         * Because we're not creating a test database we can't properly use
         * expectsTable. Hopefully I'll be able to add a test database (Rather
         * than testing against my dev one) - but in the meantime let's test a
         * few bits.
         */
        $version = GetVersion::run();
        $this->artisan('mssql:get-version')
            ->assertSuccessful()
            ->expectsOutput($version);
    }
}
