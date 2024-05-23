<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseConnectionTest extends TestCase
{
    /**
     * Test database connection.
     *
     * @return void
     */
    public function testDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            $this->assertTrue(true); // Assert that connection was successful
        } catch (\Exception $e) {
            $this->fail("Failed to connect to the database: {$e->getMessage()}");
        }
    }
}
