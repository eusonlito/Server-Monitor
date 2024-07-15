<?php

use App\Services\Logger\DeprecationsDaily;
use App\Services\Logger\LaravelDaily;
use App\Services\Logger\Mail;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => 'deprecations',
        'trace' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
            'ignore_exceptions' => false,
        ],

        'daily' => [
            'driver' => 'custom',
            'via' => LaravelDaily::class,
        ],

        'deprecations' => [
            'driver' => 'custom',
            'via' => DeprecationsDaily::class,
            'trace' => true,
        ],

        'request' => [
            'enabled' => env('LOG_REQUEST', false),
        ],

        'database' => [
            'enabled' => true,
        ],

        'mail' => [
            'driver' => 'custom',
            'via' => Mail::class,
            'enabled' => env('LOG_MAIL', true),
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],
    ],

];
