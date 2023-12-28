<?php

namespace Sajadsdi\LaravelSettingPro\Cache\Drivers;

use Sajadsdi\LaravelSettingPro\Contracts\CacheDriverInterface;
use Predis\Client as RedisClient;

class Redis implements CacheDriverInterface
{
    private RedisClient $client;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->client = new RedisClient($config['connection'],$config['options']);
    }

    /**
     * Get cache from redis database with key.
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        $value = $this->client->get($key);

        return $value ? unserialize($value) : null;
    }

    /**
     * Set Cache on redis database with key and data.
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function set(string $key, mixed $data): void
    {
        $this->client->set($key, serialize($data));
    }

    /**
     * Delete a cache with key.
     * @param string $key
     * @return void
     */
    public function clear(string $key): void
    {
        $this->client->del([$key]);
    }

    /**
     * Delete all keys from selected redis database.
     * @return void
     */
    public function clearAll(): void
    {
        $this->client->flushdb();
    }
}
