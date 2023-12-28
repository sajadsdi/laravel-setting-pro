<?php

namespace Sajadsdi\LaravelSettingPro\Drivers;

use Sajadsdi\LaravelSettingPro\Contracts\DatabaseDriverInterface;
use Sajadsdi\LaravelSettingPro\Contracts\StoreDriverInterface;

class Database implements StoreDriverInterface
{
    private DatabaseDriverInterface $driver;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->driver = new $config['connections'][$config['connection']]['class']($config['connections'][$config['connection']]);
    }

    /**
     * Get database setting with key.
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->driver->getSetting($key);
    }

    /**
     * Update database setting with key and data.
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function set(string $key, mixed $data): void
    {
        $this->driver->setSetting($key, $data);
    }

    /**
     * Delete setting row with key.
     * @param string $key
     * @return void
     */
    public function delete(string $key): void
    {
        $this->driver->deleteSetting($key);
    }
}
