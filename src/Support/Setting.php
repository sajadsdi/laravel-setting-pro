<?php

namespace Sajadsdi\LaravelSettingPro\Support;

use Sajadsdi\ArrayDotNotation\Exceptions\ArrayKeyNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingKeyNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingNotSelectedException;
use Sajadsdi\LaravelSettingPro\LaravelSettingPro;
use Sajadsdi\LaravelSettingPro\Services\SettingStore;

/**
 * @method select(string $settingName)
 */
class Setting
{
    private static self       $obj;
    private string            $select = '';
    private LaravelSettingPro $setting;

    /**
     * Create a new Setting instance.
     *
     * @param LaravelSettingPro $setting
     */
    public function __construct(LaravelSettingPro $setting)
    {
        $this->setting = $setting;
        self::$obj     = $this;
    }

    /**
     * Handle dynamic static method calls.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws SettingKeyNotFoundException
     * @throws SettingNotFoundException
     * @throws SettingNotSelectedException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (strtolower($name) == 'select') {
            return self::Obj()->selectSetting(...$arguments);
        } elseif ($arguments) {
            return self::Obj()->setting->get($name, ...$arguments);
        } else {
            return self::Obj()->selectSetting($name);
        }
    }

    /**
     * Handle dynamic method calls.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     *
     * @throws SettingKeyNotFoundException
     * @throws SettingNotFoundException
     * @throws SettingNotSelectedException
     */
    public function __call(string $name, array $arguments)
    {
        if (strtolower($name) == 'select') {
            return $this->selectSetting(...$arguments);
        } elseif ($arguments) {
            return $this->setting->get($name, ...$arguments);
        } else {
            return $this->selectSetting($name);
        }
    }

    /**
     * Get values of keys on selected setting.
     *
     * @param mixed $keys Keys to access values in the setting.
     * @param mixed|null $default Default value to return if the setting or key is not found.
     * @param bool $throw flag to disable 'NotFound' exceptions
     * @return mixed
     *
     * @throws SettingKeyNotFoundException
     * @throws SettingNotFoundException
     * @throws SettingNotSelectedException
     */
    public function get(mixed $keys = [], mixed $default = null, bool $throw = true): mixed
    {
        return $this->setting->get($this->getSelect(), $keys, $default, $throw);
    }

    /**
     * Set values of keys on selected setting.
     *
     * @param mixed $key
     * @param mixed $value
     * @return void
     * @throws SettingNotSelectedException
     */
    public function set(mixed $key, mixed $value = []): void
    {
        if ($key) {
            if (is_string($key) && $value) {
                $this->setting->set($this->getSelect(), [$key => $value]);
            } elseif (is_array($key)) {
                $this->setting->set($this->getSelect(), $key);
            }
        }
    }

    /**
     * delete keys of the selected setting.
     *
     * @param mixed $keys
     * @return void
     * @throws SettingNotSelectedException
     * @throws ArrayKeyNotFoundException
     */
    public function delete(mixed $keys = []): void
    {
        $this->setting->delete($this->getSelect(), $keys);
    }

    /**
     * @param mixed $keys
     * @return bool
     *
     * @throws SettingNotSelectedException
     */
    public function has(mixed $keys = []): bool
    {
        return $this->setting->has($this->getSelect(), $keys);
    }

    /**
     * Select a setting to work with.
     *
     * @param string $setting
     * @return Setting
     */
    private function selectSetting(string $setting = ''): Setting
    {
        $this->select = $setting;
        return $this;
    }

    /**
     * Get the currently selected setting.
     *
     * @return string
     */
    private function getSelect(): string
    {
        $select = $this->select;
        $this->selectSetting();
        return $select;
    }

    /**
     * Get the instance of the Setting class.
     *
     * @return Setting
     */
    public static function Obj(): Setting
    {
        if (!isset(self::$obj)) {
            //this added for resolve conflict with laravel bootstrap configuration
            $config = require base_path('config') . DIRECTORY_SEPARATOR . "_setting.php";

            new Setting(new LaravelSettingPro($config, new SettingStore($config)));
        }

        return self::$obj;
    }
}
