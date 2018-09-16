<?php
use PHPUnit\Framework\TestCase;
use JsonRpcClient\Request;

class RequestTest extends TestCase
{
    public function testToJson()
    {
        $requestBuilder  = new Request('PackageJsonRpc.Push', ['subscribe' => 1, 'validate' => true], 100);

        $object = json_decode($requestBuilder->toJson());
        $this->assertInstanceOf(stdClass::class, $object);
        $this->assertTrue($object->params[0]->validate);
        $this->assertEquals(1, $object->params[0]->subscribe);
        $this->assertEquals('PackageJsonRpc.Push', $object->method);
        $this->assertEquals(100, $object->id);
    }
}