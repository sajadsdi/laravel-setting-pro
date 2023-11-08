<?php

return [
    'store'          => [
        'default'     => 'database',
        'import_from' => 'file',
        'drivers'     => [
            'file'     => [
                'path'  => __DIR__ . '/../setting/',
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
    'cache'          => [
        'enabled' => false,
        'class'   => \Sajadsdi\LaravelSettingPro\Cache\Cache::class,
        'prefix'  => 'settings'
    ],
    'background_job' => false,
    'trigger_event'  => false,
    'queue'          => 'settings'
];
