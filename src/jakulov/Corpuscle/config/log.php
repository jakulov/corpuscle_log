<?php

return [
    'log' => [
        'dir' => 'log',
        'level' => \Psr\Log\LogLevel::DEBUG,
        'buffer' => true,
        'file' => 'corpuscle.log',
    ],
    'container' => [
        'di' => [
            'aware' => [
                \Psr\Log\LoggerAwareInterface::class => [
                    'setLogger' => '@service.log',
                ],
            ],
        ],
    ],
    'event' => [
        'app.shutdown' => [
            '@logger.event_listener' => ['onShutdown', -1],
        ],
    ],
    'service' => [
        'logger' => [
            'class' => \jakulov\Corpuscle\Log\Logger::class,
            'aware' => [
                'setLogStorage' => '@log_storage',
                'setConfig' => ':log',
                'setRequest' => '@request',
            ],
        ],
        'logger.event_listener' => [
            'class' => \jakulov\Corpuscle\Log\EventListener::class,
        ],
        'log_storage' => '@file_log_storage',
        'file_log_storage' => [
            'class' => \jakulov\Corpuscle\Log\FileLogStorage::class,
            'args' => [':log.dir', ':log.file'],
        ],
    ]
];