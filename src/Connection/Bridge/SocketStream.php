<?php
namespace JsonRpcClient\Connection\Bridge;

class SocketStream implements Socket
{
    /** @var int */
    const READ_BUF = 4096;

    private $sockfp;

    /** @var  int */
    private $ip;

    /** @var  int */
    private $port;

    /** @var  int */
    private $timeout;

    public function __construct($address, $timeout)
    {
        $this->timeout = $timeout;
        list($this->ip, $this->port) = explode(':', $address);
    }


    /**
     * @return array
     */
    public function getMetaData(): array
    {
        return stream_get_meta_data($this->sockfp);
    }


    public function setBlocking(): void
    {
        stream_set_blocking($this->sockfp, true);
        stream_set_timeout($this->sockfp, 0, $this->timeout * 1000);
    }

    public  function fwriteAll($content): int
    {
        for ($written = 0; $written < strlen($content); $written += $fwrite) {
            $fwrite = fwrite($this->sockfp, substr($content, $written));
            if ($fwrite === false) {
                return $written;
            }
        }
        return $written;
    }

    public function fgetsAll(): string
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
    public function open(): void
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
    public function close(): void
    {
        if (!isset($this->sockfp)) {
            return;
        }
        fclose($this->sockfp);
        $this->sockfp = null;
    }
}