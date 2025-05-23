<?php
return array (
  'utf_mode' =>
  array (
    'value' => true,
    'readonly' => true,
  ),
  'cache_flags' =>
  array (
    'value' =>
    array (
      'config_options' => 3600,
      'site_domain' => 3600,
    ),
    'readonly' => false,
  ),
  'cookies' =>
  array (
    'value' =>
    array (
      'secure' => true,
      'http_only' => true,
    ),
    'readonly' => false,
  ),
  'exception_handling' =>
  array (
    'value' =>
    array (
      'debug' => true,
      'handled_errors_types' => 4437,
      'exception_errors_types' => 4437,
      'ignore_silence' => false,
      'assertion_throws_exception' => true,
      'assertion_error_type' => 256,
      'log' => NULL,
    ),
    'readonly' => false,
  ),
  'connections' =>
  array (
    'value' =>
    array (
      'default' =>
      array (
        'className' => 'Bitrix\\Main\\DB\\MysqliConnection',
        'host' => 'localhost',
        'database' => 'sitemanager',
        'login' => 'bitrix0',
        'password' => 'z[BySpEauaN{7O0CE@6=',
        'options' => 2,
      ),
      'custom.redis' =>
      array (
        'className' => 'Bitrix\\Main\\Data\\RedisConnection',
        'port' => 6379,
        'host' => '127.0.0.1',
        'password' => '******',
        'serializer' => 1,
        'login' => '******',
        'database' => '******',
      ),
    ),
    'readonly' => true,
  ),
  'crypto' =>
  array (
    'value' =>
    array (
      'crypto_key' => '715562744c9a235fc54ac9a7cbcf11e3',
    ),
    'readonly' => true,
  ),
    'session'            => [
        'value' => [
            'mode'     => 'default',
        ],
    ],
  'smtp' =>
  array (
    'value' =>
    array (
      'enabled' => true,
      'debug' => false,
    ),
  ),
);
