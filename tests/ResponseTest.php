<?php

namespace tests;


require_once './Core/Response.php';

use Core\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testDataSet()
    {
        $data = [
            'items' => [
                '1' => 1,
                '2' => 2,
            ],
        ];

        $response = new Response();
        $response->assign($data);

        $this->assertJsonStringEqualsJsonString(json_encode($data), $response->getJsonData());
    }
}
