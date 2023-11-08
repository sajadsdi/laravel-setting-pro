<?php

namespace Sajadsdi\LaravelSettingPro\Drivers;

use Sajadsdi\LaravelSettingPro\Contracts\StoreDriverInterface;

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
        file_put_contents($this->config['path'].$key.'.php',str_replace([": "," {"," }","\n}"],[" => "," ["," ]","\n]"],"<?php\n//This file updated in ". date(DATE_ATOM) ."\nreturn " . json_encode($data, JSON_PRETTY_PRINT|JSON_NUMERIC_CHECK|JSON_PRESERVE_ZERO_FRACTION|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE ) . ';'));
    }

}
