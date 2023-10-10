<?php

namespace Sajadsdi\LaraSetting\Contracts;

use Sajadsdi\ArrayDotNotation\DotNotation;

interface StoreDriverInterface
{
    public function __construct(array $config);
    public function get(string $key): mixed;
    public function set(string $key,mixed $data): void;
}
