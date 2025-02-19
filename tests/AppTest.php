<?php

namespace tests;


require_once './App.php';

use Api\User;
use App;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    protected App $app;

    protected function setUp(): void
    {
        $config = require './config.php';
        $this->app = new App($config);
    }

    public function testApiObject(): void
    {
        $this->assertNull($this->app->getApiObject(''));
        $this->assertNull($this->app->getApiObject('NotExistsClass'));
        $this->assertInstanceOf(User::class, $this->app->getApiObject('user'));
    }
}
