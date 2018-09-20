<?php
namespace JsonRpcClient\Tests;
use JsonRpcClient\Connection\TcpConnection;
use JsonRpcClient\Connection\Bridge\SocketStream;
use JsonRpcClient\Request;

trait TcpConnectionTrait
{
    public $seqId = 1;

    /**
     * Get Tcp connection
     * @return TcpConnection
     */
    public function getTcpConnection()
    {
        $socketStream = $this->getMockBuilder(SocketStream::class)
            ->setMethods(['getMetaData', 'setBlocking', 'fwriteAll', 'fgetsAll', 'open', 'close'])
            ->setConstructorArgs(['127.0.0.1:1234', 1000])
            ->getMock();

        $request = $this->getRequest();
        $socketStream->method('fwriteAll')->willReturn(strlen($request->toJson()));
        $socketStream->method('fgetsAll')->willReturn(json_encode([
            'id' => $this->seqId,
            'result' => 'ok',
            'error' => null
        ]));


        return  new TcpConnection($socketStream);
    }

    /**
     * @return Request
     */
    protected function getRequest() : Request
    {
        return  new Request('PackageJsonRpc.Push', ['subscribe' => 1, 'validate' => true], $this->seqId);
    }
}