<?php
namespace JsonRpcClient;

use JsonRpcClient\Connection\Connection;

class Client
{
    /**
     * @var int request id
     */
    private static $SEQ_NEXT = 1;

    /** @var Connection  */
    private $connection = null;

    public function __construct(Connection $connection)
    {
        $this->connection  = $connection;
    }

    public function query(string $method, array $params) : array
    {
       $request =  new Request($method, $params, self::$SEQ_NEXT++);
       return $this->getConnection()->send($request);
    }

    /**
     * @return Connection
     */
    protected function getConnection()
    {
        return $this->connection;
    }
}