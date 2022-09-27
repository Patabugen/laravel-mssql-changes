<?php

namespace Patabugen\MssqlChanges\Tests;

use Illuminate\Support\Facades\DB;
use Patabugen\MssqlChanges\Actions\ListTables;
use Patabugen\MssqlChanges\Database;
use Patabugen\MssqlChanges\Table;

class ListTableChangesTest extends TestCase
{
    public function test_a_table_reports_its_changes()
    {
        $this->assertTrue(true);
    }
}
