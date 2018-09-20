<?php
use PHPUnit\Framework\TestCase;
use JsonRpcClient\Request;

class RequestTest extends TestCase
{
    use \JsonRpcClient\Tests\TcpConnectionTrait;

    public function testToJson()
    {
        $requestBuilder  = $this->getRequest();
        $object = json_decode($requestBuilder->toJson());
        $this->assertInstanceOf(stdClass::class, $object);
        $this->assertTrue($object->params[0]->validate);
        $this->assertEquals(1, $object->params[0]->subscribe);
        $this->assertEquals('PackageJsonRpc.Push', $object->method);
        $this->assertEquals($this->seqId, $object->id);
    }
}