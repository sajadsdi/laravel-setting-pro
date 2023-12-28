<?php

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
