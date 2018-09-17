<?php
namespace JsonRpcClient\Connection;

use JsonRpcClient\Request;

class TcpConnection implements Connection
{
    const READ_BUF = 4096;
    private $ip;
    private $port;

    private $sockfp;

    public function __construct($address)
    {
        list($this->ip, $this->port) = explode(':', $address);
    }


    public function send(Request $request, $timeout) : array
    {
        $this->open();
        $res = [
            'error' => false,
            'errorMsg' => null,
            'data' => null,
        ];
        $content = $request->toJson() . "\n";
        $written = $this->fwriteAll($content);
        if ($written != strlen($content)) {
            $res['error'] = true;
            $res['errorMsg'] = 'write request error';
            return $res;
        }
        stream_set_blocking($this->sockfp, true);
        stream_set_timeout($this->sockfp, 0, $timeout * 1000);
        $dataStr = $this->fgetsAll();

        $info = stream_get_meta_data($this->sockfp);

        if ($info['timed_out'] && empty($dataStr)) {
            $res['error'] = true;
            $res['errorMsg'] = 'time out error';
            return $res;
        }

        $data = json_decode($dataStr, true);

        if (!isset($data)) {
            $res['error'] = true;
            $res['errorMsg'] = 'respond data error: not json';
            return $res;
        }
        $res['data'] = $data;
        return $res;

    }


    private function fwriteAll($content) : int
    {
        for ($written = 0; $written < strlen($content); $written += $fwrite) {
            $fwrite = fwrite($this->sockfp, substr($content, $written));
            if ($fwrite === false) {
                return $written;
            }
        }
        return $written;
    }

    private function fgetsAll() : string
    {
        $data = '';
        while (($buffer = fgets($this->sockfp, self::READ_BUF)) !== false) {
            $data .= $buffer;
            if (substr($buffer, -1) == "\n") {
                break;
            }
        }
        return $data;
    }

    /**
     * open a tcp connection
     * @throws ConnectionException
     */
    private function open() : void
    {
        $sockfp = fsockopen('tcp://' . $this->ip, $this->port, $errno, $errstr);
        if (!$sockfp) {
            throw new ConnectionException(sprintf('open connection failed: %s %s', $errstr, $errno));
        } else {
            $this->sockfp = $sockfp;
        }
    }

    /**
     * close current tcp connection
     */
    private function close()
    {
        if (!isset($this->sockfp)) {
            return;
        }
        fclose($this->sockfp);
        $this->sockfp = null;
    }
}