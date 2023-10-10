<?php

use Sajadsdi\LaraSetting\Support\Setting;

if (!function_exists('setting')) {
    /**
     * @param string $settingName
     * @param mixed $key
     * @param mixed $default
     * @return mixed|Setting
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function setting(string $settingName = '',mixed $key = '',mixed $default = ''): mixed
    {
        $setting = app()->make(Setting::class);
        if(!$settingName){
            return $setting;
        }
        if($key){
            return $setting->select($settingName)->get($key ,$default);
        }
        return $setting->select($settingName);
    }
}
