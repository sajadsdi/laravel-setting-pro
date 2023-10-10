<?php

namespace Sajadsdi\LaraSetting\Cache;

use Sajadsdi\LaraSetting\Contracts\CacheDriverInterface;

class Cache implements CacheDriverInterface
{

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
    }

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

    /**
     * @param $key
     * @return void
     */
    public function clear($key): void
    {
        // TODO: Implement clear() method.
    }

    /**
     * @return void
     */
    public function clearAll(): void
    {
        // TODO: Implement clearAll() method.
    }
}
