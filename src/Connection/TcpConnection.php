<?php
namespace JsonRpcClient\Connection;

use JsonRpcClient\Request;
use JsonRpcClient\Connection\Bridge\Socket;


class TcpConnection implements Connection
{

    /** @var Socket  */
    private $sockfp;

    /** @var  array */
    private $response = [
        'error' => null,
        'errorMsg' => null,
        'data' => null,
    ];

    /**
     * TcpConnection constructor.
     * @param Socket $socketStream
     */
    public function __construct(Socket $socketStream)
    {
        $this->sockfp = $socketStream;
    }


    /**
     * @param Request $request
     * @return array
     */
    public function send(Request $request) : array
    {
        $socket =  $this->getSocket();
        $socket->open();

        if (!$this->checkContent($request)) {
            return $this->getResponse();
        }

        $socket->setBlocking();
        if (!$this->checkTimeOut($socket)) {
            return $this->getResponse();
        }

        if (!$this->checkResponse($socket)) {
            return $this->getResponse();
        }

        return $this->getResponse();

    }


    /**
     * @param Request $request
     * @return bool
     */
    protected function checkContent(Request $request) : bool
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


    /**
     * @param Socket $socket
     * @return bool
     */
    protected function checkTimeOut(Socket $socket)  : bool
    {
        if ($socket->getMetaData()['timed_out'] && empty($socket->fgetsAll())) {
            $this->setResponse('error', true);
            $this->setResponse('errorMsg', 'time out error');
            return false;
        }

        return true;
    }

    /**
     * @param Socket $socket
     * @return bool
     */
    protected function checkResponse(Socket $socket) : bool
    {
        $data = json_decode($socket->fgetsAll(), true);

        if (!isset($data)) {
            $this->setResponse('error', true);
            $this->setResponse('errorMsg', 'respond data error: not json');
            return false;
        }

        $this->setResponse('data', $data);
        return true;

    }

    /**
     * @param string $name
     * @param $value
     */
    protected function setResponse(string $name, $value) : void
    {
        $this->response[$name] = $value;
    }


    /**
     * @return array
     */
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