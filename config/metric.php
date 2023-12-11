<?php

return [
    /**
     * To enable or disable the metrics
     */
    'enable' => env('METRIC_ENABLE', false),

    /**
     * The listener only works between this time
     */
    'listen' => [
        'start' => '09:00',
        'end' => '15:00',
    ],

    /**
     * Redis connection name
     */
    'connection' => env('METRIC_CONNECTION', 'metric'),

    /**
     * List of connections
     */
    'redis' => [

        'metric' => [
            'url' => env('METRIC_REDIS_URL'),
            'host' => env('METRIC_REDIS_HOST', '127.0.0.1'),
            'username' => env('METRIC_REDIS_USERNAME'),
            'password' => env('METRIC_REDIS_PASSWORD'),
            'port' => env('METRIC_REDIS_PORT', '6379'),
            'database' => env('METRIC_REDIS_CACHE_DB', '3'),
        ]
    ],

    /**
     * The configuration to enable or disable handlers
     */
    'control' => [

        'query_counter' => env('METRIC_QUERY_COUNT', true),

        'query_speed' => env('METRIC_QUERY_SPEED', true),
    ],

    /**
     * The prefix for redis keys
     */
    'prefix' => [

        'query' => [

            'count' => 'metrics:query-counts',

            'speed' => 'metrics:query-speeds',
        ]
    ],

    /**
     * Notification configurations
     */
    'notify' => [

        'bot_token' => env('BOT_TOKEN'),

        'chat_id' => env('CHAT_ID'),

        'caption' => "#REPORT",

        'cron' => env('NOTIFY_TIME', '0 3 * * *'),
    ]
];
