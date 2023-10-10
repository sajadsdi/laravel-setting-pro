<?php

namespace Sajadsdi\LaraSetting\Providers;

use Illuminate\Support\ServiceProvider;
use Sajadsdi\LaraSetting\LaraSetting;
use Sajadsdi\LaraSetting\Services\SettingStore;
use Sajadsdi\LaraSetting\Support\Setting;

class LaraSettingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $config = config('lara-setting');
        $this->app->singleton(LaraSetting::class, function () use ($config) {
            return new LaraSetting($config);
        });
        $this->app->singleton(SettingStore::class, function () use ($config) {
            return new SettingStore($config);
        });

        $this->app->singleton(Setting::class);
    }

    public function boot(): void
    {

    }
}
