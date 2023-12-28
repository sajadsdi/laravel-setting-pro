<?php

namespace Sajadsdi\LaravelSettingPro\Cache;

use Sajadsdi\LaravelSettingPro\Contracts\CacheDriverInterface;

class Cache implements CacheDriverInterface
{
    private CacheDriverInterface $driver;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->driver = new $config['drivers'][$config['default']]['class']($config['drivers'][$config['default']]);
    }

    /**
     * Get cache from driver with key.
     * @param $key
     * @return mixed
     */
    public function get($key): mixed
    {
        return $this->driver->get($key);
    }

    /**
     * Set cache on driver with key and data.
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function set(string $key, mixed $data): void
    {
        $this->driver->set($key, $data);
    }

    /**
     * Clear cache from driver with key.
     * @param $key
     * @return void
     */
    public function clear($key): void
    {
        $this->driver->clear($key);
    }

    /**
     * Clear all caches from driver.
     * @return void
     */
    public function clearAll(): void
    {
        $this->driver->clearAll();
    }
}
