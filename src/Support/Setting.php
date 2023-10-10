<?php

namespace Sajadsdi\LaraSetting\Support;

use Sajadsdi\LaraSetting\Exceptions\SettingKeyNotFoundException;
use Sajadsdi\LaraSetting\Exceptions\SettingNotFoundException;
use Sajadsdi\LaraSetting\Exceptions\SettingNotSelectedException;
use Sajadsdi\LaraSetting\LaraSetting;

class Setting
{
    private static self $obj;
    private string      $select = '';
    private LaraSetting $setting;

    public function __construct(LaraSetting $setting)
    {
        $this->setting = $setting;
        self::$obj     = $this;
    }

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
     * @param array $keyValues
     * @return void
     * @throws SettingNotSelectedException
     */
    public function set(array $keyValues): void
    {
        $this->setting->set($this->getSelect(), $keyValues);
    }

    /**
     * @param string $setting
     * @return Setting
     */
    private function selectSetting(string $setting = ''): Setting
    {
        $this->select = $setting;
        return $this;
    }

    private static function Obj()
    {
        if (!isset(self::$obj)) {
            self::$obj = app()->make(self::class);
        }
        return self::$obj;
    }

    private function getSelect(): string
    {
        $select = $this->select;
        $this->selectSetting();
        return $select;
    }
}
