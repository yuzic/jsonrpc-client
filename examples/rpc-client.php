<?php
require "vendor/autoload.php";

use JsonRpcClient\Connection\TcpConnection;
use JsonRpcClient\Connection\Bridge\SocketStream;
use JsonRpcClient\Client;

$socketStream = new SocketStream('127.0.0.1:1234', 1000);
$tcpConnection =  new TcpConnection($socketStream);


$client  = new Client($tcpConnection);

$method = 'PackageJsonRpc.Push';
$params = [
    ['subscribe' => 1, 'validate' => true],
    ['message' => 'test message', 'check' => true],
];



foreach ($params as $p) {
    $result = $client->query($method, $p);

    var_dump($result);
}
