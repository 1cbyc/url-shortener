<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/bootstrap.php';
 
$migrations = glob(__DIR__ . '/migrations/*.php');
foreach ($migrations as $migration) {
    require $migration;
}
echo "Migrations completed\n"; 