<?php

return [
    'store'          => [
        'default' => 'file',
        'drivers' => [
            'file'     => [
                'path'  => __DIR__ . '/../setting/',
                'class' => \Sajadsdi\LaraSetting\Drivers\File::class,
            ],
            'database' => [
                'connection' => 'mysql',
                'class'      => \Sajadsdi\LaraSetting\Drivers\Database::class,
            ],
        ],
    ],
    'cache'          => [
        'enabled' => false,
        'class'   => \Sajadsdi\LaraSetting\Cache\Cache::class
    ],
    'background_job' => false,
    'trigger_event' => false
];
