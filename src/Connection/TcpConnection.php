<?php
namespace JsonRpcClient\Connection;

use JsonRpcClient\Request;

class TcpConnection implements Connection
{

    private $ip;
    private $port;

    public function __construct($address)
    {
        list($this->ip, $this->port) = explode(':', $address);
    }


    public function send(Request $request, $timeout) : array
    {


    }
}