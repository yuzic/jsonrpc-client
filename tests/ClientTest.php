<?php

use PHPUnit\Framework\TestCase;
use JsonRpcClient\Client;

class ClientTest extends TestCase
{
    use \JsonRpcClient\Tests\TcpConnectionTrait;

    public function testConnect()
    {
        $client  = new Client($this->getTcpConnection());

        $method = 'PackageJsonRpc.Push';
        $params = ['subscribe' => 1, 'validate' => true];
        $result = $client->query($method, $params);
        $this->assertTrue($result['data']['result'] === 'ok');
    }


}
