<?php


namespace tests;


require_once './db/Connection.php';

use db\Connection;
use PDO;
use PHPUnit\Framework\TestCase;

class DbConnectionTest extends TestCase
{
    protected $config = [];

    protected function setUp(): void
    {
        $this->config = require './config.php';
    }

    public function testDbConnectionSuccess(): void
    {
        Connection::init($this->config);
        $connection = Connection::getInstance();

        $this->assertInstanceOf(PDO::class, $connection);
    }
}
