<?php
use PHPUnit\Framework\TestCase;
use JsonRpcClient\Request;
use JsonRpcClient\Connection\TcpConnection;
use JsonRpcClient\Connection\Bridge\SocketStream;

class TcpConnectionTest extends TestCase
{

    const ID = 100;

    public function testConnect()
    {
        //$socketStream = new SocketStream('127.0.0.1:1234', 100);
        $socketStream = $this->getMockBuilder(SocketStream::class)
            ->setMethods(['getMetaData', 'setBlocking', 'fwriteAll', 'fgetsAll', 'close'])
            ->setConstructorArgs(['127.0.0.1:1234', 100])
            ->getMock();

        $request = $this->getRequest();
        $socketStream->method('fwriteAll')->willReturn(strlen($request->toJson()));
        $socketStream->method('fgetsAll')->willReturn(json_encode([
            'id' => self::ID,
            'result' => 'ok',
            'error' => null
        ]));


        $tcpConnection = new TcpConnection($socketStream);
        $responce = $tcpConnection->send($this->getRequest());
//        print_r($responce);
//        die();

        $this->assertTrue($responce['data']['error'] === null);
        $this->assertTrue($responce['data']['id'] === self::ID);
        $this->assertTrue($responce['data']['result'] === 'ok');
    }


    protected function getRequest() : Request
    {
        return  new Request('PackageJsonRpc.Push', ['subscribe' => 1, 'validate' => true], self::ID);
    }

}
