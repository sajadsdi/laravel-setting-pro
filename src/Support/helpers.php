<?php

use Illuminate\Contracts\Container\BindingResolutionException;
use Sajadsdi\LaravelSettingPro\Support\Setting;

if (!function_exists('setting')) {
    /**
     * Get or set values in the LaravelSettingPro package.
     *
     * @param string $settingName The name of the setting to retrieve or set.
     * @param mixed $key The key of the value to retrieve or set.
     * @param mixed $default The default value to return if the key does not exist.
     * @return mixed|Setting Returns a Setting instance if no arguments are provided, otherwise returns the value of the specified key.
     */
    function setting(string $settingName = '',mixed $key = '',mixed $default = ''): mixed
    {
        $setting = app(Setting::class);
        if(!$settingName){
            return $setting;
        }
        if($key){
            return $setting->select($settingName)->get($key ,$default);
        }
        return $setting->select($settingName);
    }
}
