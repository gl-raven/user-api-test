<?php


namespace tests;

require_once './Core/Router.php';

use Core\Router;
use PHPUnit\Framework\TestCase;
use Exception;

class RouterTest extends TestCase
{
    public function testDefaultRoute()
    {
        $router = new Router('');
        $this->assertEquals('user', $router->getModel());
        $this->assertEquals('index', $router->getAction());

        $router = new Router('/');
        $this->assertEquals('user', $router->getModel());
        $this->assertEquals('index', $router->getAction());
    }

    public function testInvalidRoute()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Route not found");

        $router = new Router('invalid/route/test');
    }

    public function testRouteModuleAndAction()
    {
        $router = new Router('user');
        $this->assertEquals('user', $router->getModel());
        $this->assertEquals('', $router->getAction());

        $router = new Router('user/');
        $this->assertEquals('user', $router->getModel());
        $this->assertEquals('', $router->getAction());

        $router = new Router('user/test');
        $this->assertEquals('user', $router->getModel());
        $this->assertEquals('test', $router->getAction());

        $router = new Router('user/test/');
        $this->assertEquals('user', $router->getModel());
        $this->assertEquals('test', $router->getAction());
    }

    public function testWithId()
    {
        $router = new Router('user/123/test');
        $this->assertEquals('user', $router->getModel());
        $this->assertEquals('test', $router->getAction());
        $this->assertEquals('123', $router->getParam('id'));

        $router = new Router('user/123/test/');
        $this->assertEquals('user', $router->getModel());
        $this->assertEquals('test', $router->getAction());
        $this->assertEquals('123', $router->getParam('id'));
    }

    public function testWithWrongParam()
    {
        $router = new Router('user/test/');
        $this->assertEquals(false, $router->getParam('id'));
    }
}
