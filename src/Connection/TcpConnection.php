<?php
namespace JsonRpcClient\Connection;

use JsonRpcClient\Request;
use JsonRpcClient\Connection\Bridge\Socket;


class TcpConnection implements Connection
{

    private $sockfp;

    /** @var  array */
    private $response = [
        'error' => null,
        'errorMsg' => null,
        'data' => null,
    ];

    public function __construct(Socket $socketStream)
    {
        $this->sockfp = $socketStream;
    }


    public function send(Request $request) : array
    {
        $socket =  $this->getSocket();
        $socket->open();

        if (!$this->checkContent($request)) {
            return $this->getResponse();
        }


        $socket->setBlocking();
        $dataStr = $socket->fgetsAll();

        $info = $socket->getMetaData();

        if ($info['timed_out'] && empty($dataStr)) {
            $response['error'] = true;
            $response['errorMsg'] = 'time out error';
            return $response;
        }

        $data = json_decode($dataStr, true);

        if (!isset($data)) {
            $response['error'] = true;
            $response['errorMsg'] = 'respond data error: not json';
            return $response;
        }
        $response['data'] = $data;
        return $response;

    }

    /**
     * @param Request $request
     * @return mixed array|bool
     */
    protected function checkContent(Request $request)
    {
        $content = $request->toJson();
        $written =  $this->getSocket()->fwriteAll($content);
        if ($written != strlen($content)) {
            $this->setResponse('error', true);
            $this->setResponse('errorMsg', 'write request error');

            return false;
        }

        return true;
    }

    protected function setResponse(string $name, $value)
    {
        $this->response[$name] = $value;
    }


    protected function getResponse() : array
    {
        return $this->response;
    }


    /**
     * @return Socket
     */
    protected function getSocket()
    {
        return $this->sockfp;
    }



}