<?php

namespace Sajadsdi\LaraSetting\Contracts;

interface DriverInterface
{
    public function get(string $key): mixed;
    public function set(string $key,mixed $data): void;
}
