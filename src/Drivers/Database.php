<?php

namespace Sajadsdi\LaraSetting\Drivers;

use Sajadsdi\LaraSetting\Contracts\DriverInterface;

class Database implements DriverInterface
{


    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        // TODO: Implement get() method.
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
