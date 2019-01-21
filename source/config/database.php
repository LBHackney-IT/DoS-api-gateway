<?php

return [

  /*
   |--------------------------------------------------------------------------
   | Redis Databases
   |--------------------------------------------------------------------------
   |
   | Redis is an open source, fast, and advanced key-value store that also
   | provides a richer body of commands than a typical key-value system
   | such as APC or Memcached. Laravel makes it easy to dig right in.
   |
   */

  'redis' => [

    'client' => 'predis',

    'default' => [
      'host' => env('REDIS_HOST', 'redis'),
      'password' => env('REDIS_PASSWORD', null),
      'port' => env('REDIS_PORT', 6379),
      'database' => env('REDIS_DB', 0),
    ],

    // 'cache' => [
    //   'host' => env('REDIS_HOST', '127.0.0.1'),
    //   'password' => env('REDIS_PASSWORD', null),
    //   'port' => env('REDIS_PORT', 6379),
    //   'database' => env('REDIS_CACHE_DB', 1),
    // ],

  ],


];