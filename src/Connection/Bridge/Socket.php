<?php

namespace JsonRpcClient\Connection\Bridge;

interface Socket
{
    /**
     * @return array
     */
    public function getMetaData(): array;

    public function setBlocking(): void;

    public  function fwriteAll(string $content): int;

    public function fgetsAll(): string;

    public function open(): void;

    public function close(): void;
}