<?php

use Bitrix\Main\Data\RedisConnection;
use Bitrix\Main\DB\MysqliConnection;

return [
    'utf_mode'           =>
        [
            'value'    => true,
            'readonly' => true,
        ],
    'cache_flags'        =>
        [
            'value'    =>
                [
                    'config_options' => 3600,
                    'site_domain'    => 3600,
                ],
            'readonly' => false,
        ],
    'cookies'            =>
        [
            'value'    =>
                [
                    'secure'    => true,
                    'http_only' => true,
                ],
            'readonly' => false,
        ],
    'exception_handling' =>
        [
            'value'    =>
                [
                    'debug'                      => false,
                    // 'debug' => true,
                    'handled_errors_types'       => 4437,
                    'exception_errors_types'     => 4437,
                    'ignore_silence'             => false,
                    'assertion_throws_exception' => true,
                    'assertion_error_type'       => 256,
                    'log'                        => null,
                ],
            'readonly' => false,
        ],
    'connections'        =>
        [
            'value'    =>
                [
                    'default'      =>
                        [
                            'className' => MysqliConnection::class,
                            'host'      => 'localhost',
                            'database'  => 'supersoco_db',
                            'login'     => 'supersoco_usr',
                            // 'login'     => 'root',
                            'password'  => 'kAdjnPZeeC<Wx*cF2U<e',
                            // 'password'  => 'ambient',
                            'options'   => 2,
                        ],
                    'custom.redis' => [
                        'className'  => RedisConnection::class,
                        'port'       => 6379,
                        'host'       => '127.0.0.1',
                        'password'   => 'ZS68f5eL0FyAR73m',
                        // 'password'   => 'ambient123',
                        'serializer' => Redis::SERIALIZER_PHP,
                    ],
                ],
            'readonly' => true,
        ],
    'crypto'             =>
        [
            'value'    =>
                [
                    'crypto_key' => '715562744c9a235fc54ac9a7cbcf11e3',
                ],
            'readonly' => true,
        ],
    'session'            => [
        'value' => [
            'mode'     => 'default',
            'handlers' => [
                'general' => [
                    'type'       => 'redis',
                    'port'       => '6379',
                    'host'       => '127.0.0.1',
                    'auth'       => 'ZS68f5eL0FyAR73m',
                    // 'auth'       => 'ambient123',
                    'serializer' => Redis::SERIALIZER_PHP,
                ],
            ],
        ],
    ],
    'smtp'               =>
        [
            'value' =>
                [
                    'enabled' => true,
                    'debug'   => false, //optional
                    // 'log_file' => '/var/mailer.log', //optional
                ],
        ],

];
