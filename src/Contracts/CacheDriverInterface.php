<?php

namespace Sajadsdi\LaraSetting\Contracts;

interface CacheDriverInterface
{
    public function get(string $key): mixed;
    public function set(string $key,mixed $data): void;
    public function clear($key): void;
    public function clearAll(): void;
}
