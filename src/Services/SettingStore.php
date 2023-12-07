<?php

namespace Sajadsdi\LaravelSettingPro\Services;

use Sajadsdi\LaravelSettingPro\Contracts\CacheDriverInterface;
use Sajadsdi\LaravelSettingPro\Contracts\StoreDriverInterface;

class SettingStore
{
    private array $drivers = [];
    private array $config;
    private CacheDriverInterface $cache;

    /**
     * SettingStore constructor.
     */
    public function __construct()
    {
        $this->config = config('_setting');

        if ($this->cacheEnabled()) {
            $this->setCache(new $this->config['cache']['class']($this->config['cache']));
        }
    }

    /**
     * Get the value of a setting.
     *
     * @param string $name
     * @return mixed
     */
    public function get(string $name): mixed
    {
        $data = null;
        if ($this->cacheEnabled()) {
            $data = $this->cache->get($name);
        }
        if ($data === null) {
            $data = $this->getSetting($name);
            if ($this->cacheEnabled() && $data !== null) {
                $this->cache->set($name, $data);
            }
        }
        return $data;
    }

    /**
     * Get the value of a setting from the appropriate driver.
     *
     * @param string $name
     * @return mixed
     */
    public function getSetting(string $name): mixed
    {
        $data = $this->getDriver($this->config['store']['default'])->get($name);

        if ($data === null) {
            if ($this->config['store']['import_from']) {
                $data = $this->getDriver($this->config['store']['import_from'])->get($name);
                if ($data) {
                    $this->getDriver($this->config['store']['default'])->set($name, $data);
                }
            }
        }
        return $data;
    }

    /**
     * Set the value of a setting.
     *
     * @param string $name
     * @param mixed $data
     * @return void
     */
    public function set(string $name, mixed $data): void
    {
        $this->getDriver($this->config['store']['default'])->set($name, $data);
    }

    /**
     * Check if caching is enabled.
     *
     * @return bool
     */
    private function cacheEnabled(): bool
    {
        return $this->config['cache']['enabled'];
    }

    /**
     * Get the driver instance for the given name.
     *
     * @param string $name
     * @return StoreDriverInterface
     */
    private function getDriver(string $name): StoreDriverInterface
    {
        if (!isset($this->drivers[$name])) {
            $this->setDriver($name, new $this->config['store']['drivers'][$name]['class']($this->config['store']['drivers'][$name]));
        }
        return $this->drivers[$name];
    }

    /**
     * Set the driver instance for the given name.
     *
     * @param string $name
     * @param StoreDriverInterface $class
     * @return void
     */
    private function setDriver(string $name, StoreDriverInterface $class)
    {
        $this->drivers[$name] = $class;
    }

    /**
     * Set the cache instance.
     *
     * @param CacheDriverInterface $cacheDriver
     * @return void
     */
    private function setCache(CacheDriverInterface $cacheDriver): void
    {
        $this->cache = $cacheDriver;
    }

    /**
     * Get the cache instance.
     *
     * @return CacheDriverInterface
     */
    public function cache(): CacheDriverInterface
    {
        return $this->cache;
    }
}
