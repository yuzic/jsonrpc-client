<?php
namespace JsonRpcClient\Tests\Connection;

use PHPUnit\Framework\TestCase;


use JsonRpcClient\Tests\TcpConnectionTrait;

class TcpConnectionTest extends TestCase
{
    use TcpConnectionTrait;

    public function testConnect()
    {
        //$socketStream = new SocketStream('127.0.0.1:1234', 100);
        $tcpConnection = $this->getTcpConnection();
        $responce = $tcpConnection->send($this->getRequest());

        $this->assertTrue($responce['data']['error'] === null);
        $this->assertTrue($responce['data']['id'] === $this->seqId);
        $this->assertTrue($responce['data']['result'] === 'ok');
    }




}
