<?php

namespace Sajadsdi\LaravelSettingPro\Drivers;

use Sajadsdi\LaravelSettingPro\Contracts\StoreDriverInterface;

class File implements StoreDriverInterface
{
    private array $config;
    private const DS = DIRECTORY_SEPARATOR;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config         = $config;
        $this->config['path'] = realpath(str_replace(['/', '\\'], self::DS, $this->config['path']));
    }

    /**
     * Get setting file with key name.
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        $data = null;
        $file = $this->config['path'] . self::DS . $key . '.php';
        if (file_exists($file)) {
            $data = require $file;
        }
        return $data;
    }

    /**
     * Update setting file with key name and data.
     * @param string $key
     * @param mixed $data
     * @return void
     */
    public function set(string $key, mixed $data): void
    {
        file_put_contents($this->config['path'] . self::DS . $key . '.php', str_replace([": ", " {", " }", "\n}"], [" => ", " [", " ]", "\n]"], "<?php\n//This file updated in " . date(DATE_ATOM) . "\nreturn " . json_encode($data, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ';'));
    }

    /**
     * Delete setting file with key name.
     * @param string $key
     * @return void
     */
    public function delete(string $key): void
    {
        $file = $this->config['path'] . self::DS . $key . '.php';
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
