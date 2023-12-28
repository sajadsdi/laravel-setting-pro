<?php

namespace Sajadsdi\LaravelSettingPro\Cache\Drivers;

use Sajadsdi\LaravelSettingPro\Contracts\CacheDriverInterface;

class File implements CacheDriverInterface
{
    private string $path;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->path = realpath($config['path']);

        if(!is_dir($this->path)){
            mkdir($this->path,0777,true);
        }
    }

    /**
     * Get cache file with key.
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        if (file_exists($file = $this->getFile($key))) {
            return unserialize(file_get_contents($file));
        }

        return null;
    }

    /**
     * Set cache file with key and data.
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function set(string $key, mixed $data): void
    {
        file_put_contents($this->getFile($key), serialize($data));
    }

    /**
     * Delete a cache file with key.
     * @param $key
     * @return void
     */
    public function clear($key): void
    {
        if (file_exists($file = $this->getFile($key))) {
            unlink($file);
        }
    }

    /**
     * Get cache file path.
     * @param string $key
     * @return string
     */
    private function getFile(string $key): string
    {
        return $this->path . DIRECTORY_SEPARATOR . md5($key);
    }

    /**
     * Delete setting cache directory and create again.
     * @return void
     */
    public function clearAll(): void
    {
        $files = scandir($this->path);

        foreach ($files as $file) {
            if($file != '.' && $file != '..') {
                unlink($this->path . DIRECTORY_SEPARATOR . $file);
            }
        }
    }
}
