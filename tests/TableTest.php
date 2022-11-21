<?php

namespace Patabugen\MssqlChanges\Tests;

use Patabugen\MssqlChanges\Table;

class TableTest extends TestCase
{
    public function test_error_thrown_if_creating_invalid_table()
    {
        $this->expectExceptionMessage("A table named 'not-a-real-table' was not found");
        Table::create('not-a-real-table');
    }

    public function test_we_can_create_a_table_from_its_name()
    {
        $table = Table::create('Contacts');
        $this->assertInstanceOf(Table::class, $table);
        $this->assertEquals('Contacts', $table->name);
        $this->assertEquals('ContactID', $table->primaryKeyName);
        $this->assertTrue($table->columnTrackingEnabled);
    }
}
