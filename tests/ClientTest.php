<?php

use PHPUnit\Framework\TestCase;
use JsonRpcClient\Client;

class ClientTest extends TestCase
{
    public function testConnect()
    {
        $client  = new Client;

        $this->assertTrue($client->query());
    }
}
