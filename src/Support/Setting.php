<?php

namespace Sajadsdi\LaravelSettingPro\Support;

use Illuminate\Contracts\Container\BindingResolutionException;
use Sajadsdi\ArrayDotNotation\Exceptions\ArrayKeyNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingKeyNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingNotFoundException;
use Sajadsdi\LaravelSettingPro\Exceptions\SettingNotSelectedException;
use Sajadsdi\LaravelSettingPro\LaravelSettingPro;

class Setting
{
    private static self $obj;
    private string $select = '';
    private LaravelSettingPro $setting;

    /**
     * Create a new Setting instance.
     *
     * @param LaravelSettingPro $setting
     */
    public function __construct(LaravelSettingPro $setting)
    {
        $this->setting = $setting;
        self::$obj = $this;
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
     * @param mixed $keys
     * @param mixed $default
     * @return mixed
     * @throws SettingNotSelectedException
     * @throws SettingKeyNotFoundException
     * @throws SettingNotFoundException
     */
    public function get(mixed $keys = '', mixed $default = null): mixed
    {
        return $this->setting->get($this->getSelect(), $keys, $default);
    }

    /**
     * Set values of keys on selected setting.
     *
     * @param array $keyValues
     * @return void
     * @throws SettingNotSelectedException
     */
    public function set(array $keyValues): void
    {
        $this->setting->set($this->getSelect(), $keyValues);
    }

    /**
     * delete keys of the selected setting.
     *
     * @param array|string|null $keys
     * @return void
     * @throws SettingNotSelectedException
     * @throws ArrayKeyNotFoundException
     */
    public function delete(array|string $keys = null): void
    {
        $this->setting->delete($this->getSelect(), $keys);
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
    private static function Obj(): Setting
    {
        if (!isset(self::$obj)) {
            self::$obj = app(self::class);
        }
        return self::$obj;
    }
}
