<?php

namespace Patabugen\MssqlChanges\Tests;

use Illuminate\Support\Facades\DB;
use Patabugen\MssqlChanges\Actions\ListTables;
use Patabugen\MssqlChanges\Database;

class ListTablesTest extends TestCase
{
    public function test_we_can_list_databases_with_tracking_enabled()
    {
        // TODO: Create these databases/tables in the test.
        $database = Database::create();
        $databases = ListTables::run();
        $this->assertCount(1, $databases);
    }
}
