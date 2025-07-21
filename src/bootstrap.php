<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

if (getenv('DB_CONNECTION') === 'sqlite') {
    $capsule->addConnection([
        'driver' => 'sqlite',
        'database' => getenv('DB_DATABASE'),
        'prefix' => '',
    ]);
} else {
    $capsule->addConnection([
        'driver' => 'mysql',
        'host' => getenv('DB_HOST'),
        'database' => getenv('DB_DATABASE'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
    ]);
}

$capsule->setAsGlobal();
$capsule->bootEloquent(); 