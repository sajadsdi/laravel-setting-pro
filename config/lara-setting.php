<?php

return [
    'store'          => [
        'default'     => 'database',
        'import_from' => 'file',
        'drivers'     => [
            'file'     => [
                'path'  => __DIR__ . '/../setting/',
                'class' => \Sajadsdi\LaraSetting\Drivers\File::class,
            ],
            'database' => [
                'connection' => 'mysql',
                'class'      => \Sajadsdi\LaraSetting\Drivers\Database::class,
                'models'     => [
                    'mysql'   => \Sajadsdi\LaraSetting\Model\MysqlSetting::class,
                    'mongodb' => \Sajadsdi\LaraSetting\Model\MongoSetting::class
                ]
            ],
        ],
    ],
    'cache'          => [
        'enabled' => true,
        'class'   => \Sajadsdi\LaraSetting\Cache\Cache::class,
        'prefix'  => 'settings'
    ],
    'background_job' => false,
    'trigger_event'  => false,
    'queue'          => 'setting'
];
