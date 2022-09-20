<?php

namespace Patabugen\MssqlChanges\Tests;

use Patabugen\MssqlChanges\Actions\ListDatabases;

class ListDatabasesTest extends TestCase
{
    public function test_we_can_list_databases_with_tracking_enabled()
    {
        // TODO: Create these databases/tables in the test.
        $databases = ListDatabases::run();
        $this->assertCount(1, $databases);
    }
}
