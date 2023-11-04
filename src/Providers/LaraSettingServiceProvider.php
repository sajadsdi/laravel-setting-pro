<?php

namespace Sajadsdi\LaraSetting\Providers;

use Illuminate\Support\ServiceProvider;
use Sajadsdi\LaraSetting\Console\InstallCommand;
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
        $this->configurePublishing();
        $this->migrationPublishing();
        $this->registerCommands();
    }

    private function configurePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../config/lara-setting.php' => config_path('lara-setting.php')], 'lara-setting');
        }
    }

    private function migrationPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../database/migrations/2023_11_03_030451_create_setting_table.php' => database_path('migrations/2023_11_03_030451_create_setting_table.php')], 'lara-setting');
        }
    }

    private function registerCommands()
    {
        $this->commands([
            InstallCommand::class
        ]);
    }

}
