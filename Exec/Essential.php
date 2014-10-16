<?php

include __DIR__ . "/../vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection([
   'driver' => 'mysql',
   'host' => 'localhost',
   'database' => 'chat',
   'username' => 'root',
   'password' => 'anncoulter',
   'charset' => 'utf8',
   'collation' => 'utf8_unicode_ci',
   'prefix' => '',
]);
$capsule->bootEloquent();

DB::$user = 'root';
DB::$password = 'anncoulter';
DB::$dbName = 'chat';
DB::$host = 'localhost';

// And our libraries
foreach (glob(__DIR__ . "/../Libs/*.php") as $filename) {
   include $filename;
}
// And our objects
foreach (glob(__DIR__ . "/../Objects/*.php") as $filename) {
   include $filename;
}
