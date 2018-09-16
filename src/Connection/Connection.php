<?php
namespace JsonRpcClient\Connection;
use JsonRpcClient\Request;

interface Connection
{
    /**
     * Sended request on json-rpc server
     * @param Request $request
     * @param $timeout
     * @return array
     */
    public function send(Request $request, $timeout) : array;
}
