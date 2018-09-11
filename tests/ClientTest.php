<?php

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testConnect()
    {
        $client  = new Client;

        $this->assertTrue($client->connect());
    }
}
