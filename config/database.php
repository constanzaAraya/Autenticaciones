<?php 

return [
    
    'default' => 'local',

    'migrations' => 'migrations',

    'fetch' => PDO::FETCH_CLASS,

    'connections' => [
        'local' => [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
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