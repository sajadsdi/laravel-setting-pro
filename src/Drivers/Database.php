<?php

namespace Sajadsdi\LaravelSettingPro\Drivers;

use Sajadsdi\LaravelSettingPro\Contracts\DatabaseModelInterface;
use Sajadsdi\LaravelSettingPro\Contracts\StoreDriverInterface;

class Database implements StoreDriverInterface
{
    private DatabaseModelInterface $model;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->model = new $config['models'][$config['connection']];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->model->getSetting($key);
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function set(string $key, mixed $data): void
    {
        $this->model->setSetting($key, $data);
    }
}
