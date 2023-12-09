<?php

namespace Sajadsdi\LaravelSettingPro\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Sajadsdi\LaravelSettingPro\Console\InstallCommand;
use Sajadsdi\LaravelSettingPro\Console\PublishCommand;
use Sajadsdi\LaravelSettingPro\Console\PublishMongoDBCommand;
use Sajadsdi\LaravelSettingPro\LaravelSettingPro;
use Sajadsdi\LaravelSettingPro\Services\SettingStore;
use Sajadsdi\LaravelSettingPro\Support\Setting;

class LaravelSettingProServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingStore::class);

        $this->app->singleton(LaravelSettingPro::class);

        $this->app->singleton(Setting::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->configurePublishing();
            $this->migrationPublishing();
            $this->registerCommands();
        }
    }

    private function configurePublishing()
    {
        $this->publishes([__DIR__ . '/../../config/_setting.php' => config_path('_setting.php')], 'laravel-setting-pro-configure');
    }

    private function migrationPublishing()
    {
        $this->publishes([__DIR__ . '/../../database/migrations/2023_11_03_030451_create_settings_table.php' => database_path('migrations/2023_11_03_030451_create_settings_table.php')], 'laravel-setting-pro-migration');
        $this->publishes([__DIR__ . '/../../database/migrations/2023_12_08_042350_create_settings_mongodb_collection.php' => database_path('migrations/2023_12_08_042350_create_settings_mongodb_collection.php')], 'laravel-setting-pro--mongodb-migration');
    }

    private function registerCommands()
    {
        $this->commands([
            PublishCommand::class,
            InstallCommand::class,
            PublishMongoDBCommand::class
        ]);
    }

}
