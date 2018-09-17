<?php
use PHPUnit\Framework\TestCase;
use JsonRpcClient\Request;
use JsonRpcClient\Connection\TcpConnection;

class TcpConnectionTest extends TestCase
{

    public function testConnect()
    {
        $tcpConnection = new TcpConnection('127.0.0.1:1234');
        $responce = $tcpConnection->send($this->getRequest(), 100);
        $this->assertTrue($responce['error'] === false);
    }


    protected function getRequest() : Request
    {
        return  new Request('PackageJsonRpc.Push', ['subscribe' => 1, 'validate' => true], 100);
    }
}
