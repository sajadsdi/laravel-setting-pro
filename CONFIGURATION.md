## Laravel Setting Pro Configuration
Explore and customize the Laravel Setting Pro package with the following configuration options tailored to your needs.

Here is the configuration file for Laravel Setting Pro `_setting.php`:

```php
return [
    'store'            => [
        //Default setting store driver
        'default'     => 'file',

        //If any requested setting is not found in the default driver,
        //this driver is used and if available, it is imported into
        //the default driver. you can set empty to disable this feature.
        'import_from' => '',

        //Include drivers and configures for setting store
        'drivers'     => [
            'file'     => [
                'path'  => base_path('setting'),
                'class' => \Sajadsdi\LaravelSettingPro\Drivers\File::class,
            ],
            'database' => [
                //Default database connection if default store is 'database'
                'connection'  => 'mysql',
                'class'       => \Sajadsdi\LaravelSettingPro\Drivers\Database::class,
                'connections' => [
                    'mysql'   => [
                        'class'    => \Sajadsdi\LaravelSettingPro\Drivers\Database\Mysql::class,
                        'driver'   => "mysql",
                        'host'     => env('DB_HOST', '127.0.0.1'),
                        'port'     => env('DB_PORT', '3306'),
                        'database' => env('DB_DATABASE', 'forge'),
                        'username' => env('DB_USERNAME', 'forge'),
                        'password' => env('DB_PASSWORD', ''),
                        'charset'  => 'utf8mb4',
                        'prefix'   => '',
                    ],
                    'mongodb' => [
                        'class'    => \Sajadsdi\LaravelSettingPro\Drivers\Database\MongoDB::class,
                        'host'     => env('MONGO_DB_HOST', '127.0.0.1'),
                        'port'     => env('MONGO_DB_PORT', 27017),
                        'database' => env('MONGO_DB_DATABASE', 'forge'),
                        'username' => env('MONGO_DB_USERNAME', ''),
                        'password' => env('MONGO_DB_PASSWORD', ''),
                        'options'  => [

                        ],
                    ]
                ]
            ],
        ],
    ],
    'cache'            => [
        //flag for enable or disable cache.
        'enabled' => false,

        //Default setting cache driver
        'default' => 'file',
        'class'   => \Sajadsdi\LaravelSettingPro\Cache\Cache::class,

        //Include drivers and configures for setting cache
        'drivers' => [
            'file'  => [
                'class' => \Sajadsdi\LaravelSettingPro\Cache\Drivers\File::class,
                'path'  => storage_path('framework/cache/settings'),
            ],
            'redis' => [
                'class'      => \Sajadsdi\LaravelSettingPro\Cache\Drivers\Redis::class,
                'connection' => [
                    'scheme'   => 'tcp',
                    'host'     => env('REDIS_HOST', '127.0.0.1'),
                    'username' => env('REDIS_USERNAME'),
                    'password' => env('REDIS_PASSWORD'),
                    'port'     => env('REDIS_PORT', '6379'),
                    'database' => env('REDIS_CACHE_DB', '1'),
                ],
                'options'    => [
                    'cluster' => env('REDIS_CLUSTER', 'redis'),
                    'prefix'  => 'setting_caches_',
                ],
            ]
        ]
    ],

    //If true ,update and delete Settings handled on background.
    'process_to_queue' => false,
    'queue'            => 'settings',

    //If true ,trigger events after update and delete settings. you can create listener for:
    //Sajadsdi\LaravelSettingPro\Events\UpdateSettingEvent
    //Sajadsdi\LaravelSettingPro\Events\DeleteSettingEvent
    'trigger_events'   => false
];

```


Now, let's break down the purpose of each key in this configuration:

1. **`store` Section:**

    - **`default`**: Sets the default storage for settings. It is currently configured for `file`.
    - **`import_from`**: Defines the initial storage for loading settings (If any requested setting is not found in the default driver), currently is not set.
    - **`drivers`**: Configures different drivers for storing settings.

        - **`file`**: Configures file storage settings, including the file path (`path`) and the driver class.
        - **`database`**: Configures database storage settings, including the database `connection` for default, `class`, and `Connections` for configure each database . You can choose `mysql` or `mongodb` or any defined connection driver. 
        - if you need use mongodb ,you must install `mongodb/mongodb`
        package and set connection config for mongodb, and run artisan command `php artisan setting:publish-mongodb` and `php artisan migrate`

2. **`cache` Section:**

    - **`enabled`**: Toggles caching for settings. Currently, caching is disabled.
    - **`default`**: Set the default cache driver.
    - **`class`**: Specifies the class related to settings caching.
    - **`drivers`**: Include drivers and configures for setting cache. You can add new cache driver if needed.

      - **`file`**:  Configures file cache settings, including the directory path (path) and the driver class.
      - **`redis`**:  Configures redis cache settings, including driver class and connection configs.
      - if you need use `redis` for cache driver, you must install `predis/predis` package and setup connection.

3. **`process_to_queue` Section:**

    - **`process_to_queue`**: Determines whether a queue should process settings update and delete. Currently, this feature is disabled.If users want to grant expensive update and delete operations, it's advised to use a separate queue name and single separate worker only for setting.

4. **`queue` Section:**

    - **`queue`**: Specifies the queue name for processing settings if `process_to_queue` is enabled.

5. **`trigger_events` Section:**

    - **`trigger_events`**: Indicates whether an update or delete events should be triggered when a setting is updated or deleted. Currently, this feature is disabled.

This configuration provides flexibility and control over various aspects of Laravel Setting Pro, allowing you to tailor it to your specific requirements.
