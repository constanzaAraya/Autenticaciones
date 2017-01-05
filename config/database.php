<?php 

    return [
        
        'default' => 'mysql',

        'migrations' => 'migrations',

        'fetch' => PDO::FETCH_CLASS,

        'connections' => [
            'mysql' => [
                'driver' => env('DB_CONNECTION', 'mysql'),
                'host' => env('DB_HOST', '192.168.11.74'),
                'database' => env('DB_DATABASE', 'db_auth'),
                'username' => env('DB_USERNAME', 'dbuser_auth'),
                'password' => env('DB_PASSWORD', 'auth_enor123.'),
                'charset' => 'utf8',
                'collation' => 'utf8_general_ci',
                'prefix' => '',
                'strict' => 'false'
            ],
        ],
    ];
?>