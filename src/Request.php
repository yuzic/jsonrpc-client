<?php
namespace JsonRpcClient;

class Request
{
    protected $method;

    protected $params;

    protected $id;


    public function __construct(string $method, array $params, int $id)
    {
        $this->method = $method;
        $this->params = $params;
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode([
            'method' => $this->method,
            'params' => [$this->params],
            'id' => $this->id,
        ]);
    }
}