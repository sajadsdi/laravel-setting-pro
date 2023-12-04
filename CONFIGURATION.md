## Laravel Setting Pro Configuration
Explore and customize the Laravel Setting Pro package with the following configuration options tailored to your needs.

Here is the configuration file for Laravel Setting Pro `laravel-setting.php`:

```php
<?php

return [
    'store'                => [
        'default'     => 'database',
        'import_from' => 'file',
        'drivers'     => [
            'file'     => [
                'path'  => base_path('setting/'),
                'class' => \Sajadsdi\LaravelSettingPro\Drivers\File::class,
            ],
            'database' => [
                'connection' => 'mysql',
                'class'      => \Sajadsdi\LaravelSettingPro\Drivers\Database::class,
                'models'     => [
                    'mysql'   => \Sajadsdi\LaravelSettingPro\Model\MysqlSetting::class,
                    'mongodb' => \Sajadsdi\LaravelSettingPro\Model\MongoSetting::class
                ]
            ],
        ],
    ],
    'cache'                => [
        'enabled' => false,
        'class'   => \Sajadsdi\LaravelSettingPro\Cache\Cache::class,
        'prefix'  => 'settings'
    ],
    'process_to_queue'     => false,
    'queue'                => 'settings',
    'trigger_update_event' => false,
];
```

Now, let's break down the purpose of each key in this configuration:

1. **`store` Section:**

    - **`default`**: Sets the default storage for settings. It is currently configured for `database`.
    - **`import_from`**: Defines the initial storage for loading settings, currently set to `file`.
    - **`drivers`**: Configures different drivers for storing settings.

        - **`file`**: Configures file storage settings, including the file path (`path`) and the driver class.
        - **`database`**: Configures database storage settings, including the database connection (`connection`), driver class, and associated models for each database.

2. **`cache` Section:**

    - **`enabled`**: Toggles caching for settings. Currently, caching is disabled.
    - **`class`**: Specifies the class related to settings caching.
    - **`prefix`**: Sets the prefix for cache keys related to settings.

3. **`process_to_queue` Section:**

    - **`process_to_queue`**: Determines whether a queue should process settings updates. Currently, this feature is disabled.If users want to grant expensive update operations, it's advised to use a separate queue name and single separate worker only for setting.

4. **`queue` Section:**

    - **`queue`**: Specifies the queue name for processing settings if a queue is used.

5. **`trigger_update_event` Section:**

    - **`trigger_update_event`**: Indicates whether an update event should be triggered when a setting is updated. Currently, this feature is disabled.

This configuration provides flexibility and control over various aspects of Laravel Setting Pro, allowing you to tailor it to your specific requirements.
