<?php

namespace tests;


require_once './Core/Request.php';

use Core\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    private ?Request $request = null;

    protected function setUp(): void
    {
        $this->request = new Request();
    }

    public function testChangeMethod()
    {
        $method = Request::GET;
        $this->assertEquals($method, $this->request->getMethod());

        $method = Request::POST;
        $this->request->setMethod($method);
        $this->assertEquals($method, $this->request->getMethod());

        $method = 'WRONG';
        $this->request->setMethod($method);
        $this->assertNotEquals($method, $this->request->getMethod());
        $this->assertEquals(Request::POST, $this->request->getMethod());
    }

    public function testData()
    {
        $this->assertNull($this->request->get('get1', Request::GET));
        $this->assertNull($this->request->get('post', Request::POST));

        $get = [
            'get' => 'get value',
        ];
        $this->request->setMethod(Request::GET);
        $this->request->setData($get);
        $this->assertEquals($get['get'], $this->request->get('get', Request::GET));
        $this->assertNull($this->request->get('get', Request::POST));

        $post = [
            'post' => 'post value',
        ];
        $this->request->setMethod(Request::POST);
        $this->request->setData($post);
        $this->assertEquals($post['post'], $this->request->get('post', Request::POST));
        $this->assertNull($this->request->get('post', Request::GET));

        $get = [
            'get1' => 'get1 value',
        ];
        $this->request->setMethod(Request::GET);
        $this->request->setData($get);
        $this->assertEquals($get['get1'], $this->request->get('get1', Request::GET));
        $this->assertNull($this->request->get('get', Request::GET));
    }
}
