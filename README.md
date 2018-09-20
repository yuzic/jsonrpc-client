# JSON-RPC client for PHP


## Overview

This package based on JSON-RPC 1.0 Specification

[specification_v1](https://www.jsonrpc.org/specification_v1).



## Features

* Correct: fully compliant with the [JSON-RPC 2.0 specifications](http://www.jsonrpc.org/specification)



## Examples

### Client

```php
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
```

### Server

*Server based on on this GOLANG lib [examples](https://github.com/yuzic/mqueue).*


## Requirements

* PHP >= 7.2.9


## License

This package is released under an open-source license: [LGPL-3.0](https://www.gnu.org/licenses/lgpl-3.0.html)


## Installation

If you're using [Composer](https://getcomposer.org/), you can include this library
([itcoder/jsonrpc-client](https://packagist.org/packages/itcoder/jsonrpc-client)) like this:
```
composer require "itcoder/jsonrpc-client" "~0.1"
```


## Getting started

1. Try the examples. You can run the examples from the project directory like this:
	```
	php examples/rpc-client.php
	```


## Unit tests

You can run the suite of unit tests from the project directory like this:
```
php ./phpunit.phar
```

## Author

[Yuri Zhigadlo] (https://github.com/yuzic)