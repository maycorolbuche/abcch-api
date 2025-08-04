<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '127.0.0.1'),
            'port'      => env('DB_PORT', '3306'),
            'database'  => env('DB_DATABASE', 'forge'),
            'username'  => env('DB_USERNAME', 'forge'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => true,
            'engine'    => null,
        ],

        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_SQLSERVER_HOST', 'localhost'),
            'port'     => env('DB_SQLSERVER_PORT', '1433'),
            'database' => env('DB_SQLSERVER_DATABASE', 'forge'),
            'username' => env('DB_SQLSERVER_USERNAME', 'forge'),
            'password' => env('DB_SQLSERVER_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
            'trust_server_certificate' => env('DB_SQLSERVER_TRUST_SERVER_CERTIFICATE', false),
        ],
        /*
        'sqlsrv_news' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_SQLSERVER_NEWS_HOST', 'localhost'),
            'port'     => env('DB_SQLSERVER_NEWS_PORT', '1433'),
            'database' => env('DB_SQLSERVER_NEWS_DATABASE', 'forge'),
            'username' => env('DB_SQLSERVER_NEWS_USERNAME', 'forge'),
            'password' => env('DB_SQLSERVER_NEWS_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
            'encrypt' => 'yes',
            'trust_server_certificate' => true,
        ],*/
    ],
];
