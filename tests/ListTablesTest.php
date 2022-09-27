<?php

namespace Patabugen\MssqlChanges\Tests;

use Illuminate\Support\Facades\DB;
use Patabugen\MssqlChanges\Actions\ListTables;
use Patabugen\MssqlChanges\Database;
use Patabugen\MssqlChanges\Table;

class ListTablesTest extends TestCase
{
    public function test_we_can_list_tables_with_tracking_enabled()
    {
        // TODO: Create these databases/tables in the test.
        $tables = ListTables::run();
        $this->assertCount(135, $tables);
        $this->assertContainsOnlyInstancesOf(Table::class, $tables);
        $this->assertArrayHasKey('Contacts', $tables);
        $this->assertArrayHasKey('Transactions', $tables);
        $this->assertArrayHasKey('TransactionLines', $tables);
    }
}
