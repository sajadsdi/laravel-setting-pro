<?php

namespace Sajadsdi\LaravelSettingPro\Providers;

use Illuminate\Support\ServiceProvider;
use Sajadsdi\LaravelSettingPro\Console\InstallCommand;
use Sajadsdi\LaravelSettingPro\Console\PublishCommand;
use Sajadsdi\LaravelSettingPro\LaravelSettingPro;
use Sajadsdi\LaravelSettingPro\Services\SettingStore;
use Sajadsdi\LaravelSettingPro\Support\Setting;

class LaravelSettingProServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $config = config('laravel-setting');

        $this->app->singleton(SettingStore::class, function () use ($config) {
            return new SettingStore($config);
        });

        $this->app->singleton(LaravelSettingPro::class, function () use ($config) {
            return new LaravelSettingPro($config);
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
            $this->publishes([__DIR__ . '/../../config/laravel-setting.php' => config_path('laravel-setting.php')], 'laravel-setting-pro-configure');
        }
    }

    private function migrationPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../../database/migrations/2023_11_03_030451_create_setting_table.php' => database_path('migrations/2023_11_03_030451_create_setting_table.php')], 'laravel-setting-pro-migration');
        }
    }

    private function registerCommands()
    {
        $this->commands([
            PublishCommand::class,
            InstallCommand::class
        ]);
    }

}
