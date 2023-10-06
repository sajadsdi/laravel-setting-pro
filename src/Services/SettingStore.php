<?php

namespace Sajadsdi\LaraSetting\Services;

use Sajadsdi\LaraSetting\Contracts\DriverInterface;

class SettingStore
{
    private array           $drivers = [];
    private array           $config  = [];
    private DriverInterface $cache;

    public function __construct(array $config = [])
    {
        $this->config = $config ? $config : config('lara-setting');
        if ($this->cacheEnabled()) {
            $this->cache = new $this->config['cache']['class'];
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name): mixed
    {
        $data = null;
        if ($this->cacheEnabled()) {
            $data = $this->cache->get($name);
        }
        if ($data === null) {
            $data = $this->getDriver($this->config['store']['default'])->get($name);
            if ($this->cacheEnabled() && $data !== null) {
                $this->cache->set($name, $data);
            }
        }
        return $data;
    }

    /**
     * @param string $name
     * @param mixed $data
     * @return void
     */
    public function set(string $name, mixed $data): void
    {
        $this->getDriver($this->config['store']['default'])->set($name, $data);
        if ($this->cacheEnabled()) {
            $this->cache->set($name, $data);
        }
    }

    /**
     * @return bool
     */
    private function cacheEnabled(): bool
    {
        return $this->config['cache']['enabled'];
    }

    /**
     * @param string $name
     * @return DriverInterface
     */
    private function getDriver(string $name): DriverInterface
    {
        if (!isset($this->drivers[$name])) {
            $this->setDriver($name,new $this->config['store']['drivers'][$name]['class']);
        }
        return $this->drivers[$name];
    }

    /**
     * @param string $name
     * @param DriverInterface $class
     * @return void
     */
    private function setDriver(string $name, DriverInterface $class)
    {
        $this->drivers[$name] = $class;
    }
}
