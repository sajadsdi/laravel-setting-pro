<?php

namespace Sajadsdi\LaraSetting\Drivers;

use Sajadsdi\LaraSetting\Contracts\StoreDriverInterface;

class File implements StoreDriverInterface
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
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        $data = null;
        $file = $this->config['path'].$key.'.php';
        if(file_exists($file)) {
            $data = require $this->config['path'] . $key . '.php';
        }
        return $data;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function set(string $key, mixed $data): void
    {
        file_put_contents($this->config['path'].$key.'.php',"<?php\n\nreturn " . str_replace(['array (',')'],['[',']'],var_export($data,true)) . ';');
    }

}
