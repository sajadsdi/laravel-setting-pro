<?php

namespace Sajadsdi\LaravelSettingPro\Cache;

use Sajadsdi\LaravelSettingPro\Contracts\CacheDriverInterface;
use Illuminate\Support\Facades\Cache as laravelCache;

class Cache implements CacheDriverInterface
{
    private array $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key): mixed
    {
        return laravelCache::get($this->config['prefix'] . '.' . $key);
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function set(string $key, mixed $data): void
    {
        laravelCache::put($this->config['prefix'] . '.' . $key, $data);
    }

    /**
     * @param $key
     * @return void
     */
    public function clear($key): void
    {
        laravelCache::forget($this->config['prefix'] . '.' . $key);
    }

    /**
     * @return void
     */
    public function clearAll(): void
    {
        laravelCache::flush();
    }
}
