<?php

namespace Sajadsdi\LaraSetting\Drivers;

use Sajadsdi\LaraSetting\Contracts\DriverInterface;

class Cache implements DriverInterface
{

    /**
     * @param $key
     * @return mixed
     */
    public function get($key): mixed
    {
        // TODO: Implement load() method.
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function set(string $key, mixed $data): void
    {
        // TODO: Implement set() method.
    }
}
