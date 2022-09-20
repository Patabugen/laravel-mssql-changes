<?php

namespace Patabugen\MssqlChanges\Tests;

use Patabugen\MssqlChanges\Actions\ListDatabases;

class ListDatabasesTest extends TestCase
{
    public function test_we_can_list_databases_with_tracking_enabled()
    {
        $expectedTables = [

        ];

        $databases = ListDatabases::run();
        $this->assertCount(135, $databases);
    }
}
