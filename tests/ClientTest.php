<?php

use PHPUnit\Framework\TestCase;
use JsonRpcClient\Client;
use JsonRpcClient\Connection\TcpConnection;
use JsonRpcClient\Connection\Bridge\SocketStream;

class ClientTest extends TestCase
{
    public function testConnect()
    {
        $socketStream = new SocketStream('127.0.0.1:1234', 100);
        $tcpConnection = new TcpConnection($socketStream);
        $client  = new Client($tcpConnection);

        $method = 'PackageJsonRpc.Push';
        $params = ['subscribe' => 1, 'validate' => true];

        $result = $client->query($method, $params);

        $this->assertTrue($result['data']['result'] === 'ok');
    }
}
