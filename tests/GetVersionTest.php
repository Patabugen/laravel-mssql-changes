<?php

namespace Patabugen\MssqlChanges\Tests;

use Patabugen\MssqlChanges\Actions\GetVersion;
use Patabugen\MssqlChanges\Models\Contact;

class GetVersionTest extends TestCase
{
    public function test_get_version_reports_correct_version()
    {
        $version = GetVersion::run();
        Contact::factory()->create();
        $this->assertEquals($version + 1, GetVersion::run());
    }

    public function test_we_can_get_version_from_artisan()
    {
        $version = GetVersion::run();
        Contact::factory()->create();

        $this->artisan('mssql:get-version')
            ->assertSuccessful()
            ->expectsOutput($version + 1);
    }
}
