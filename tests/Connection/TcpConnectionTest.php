<?php
use PHPUnit\Framework\TestCase;
use JsonRpcClient\Request;
use JsonRpcClient\Connection\TcpConnection;

class TcpConnectionTest extends TestCase
{
    public function testConnect()
    {
        $tcpConnection = new TcpConnection('127.0.0.1:1234');
        $this->assertTrue($tcpConnection->send($this->getRequest(), 1));
    }


    protected function getRequest() : Request
    {
        return  new Request('PackageJsonRpc.Push', ['subscribe' => 1, 'validate' => true], 100);
    }
}
