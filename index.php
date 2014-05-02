<?php
/**
 * A generic API application.
 */

// Bootstrap execution using Essential.php
// This also includes the composer autoloader and stuff.
require('Exec/Essential.php');

// Create the application instance.
$app = new \Slim\Slim();

// The 0.1 api
$app->group('/0.1', function () use ($app) {
   foreach (glob("0.1/*.php") as $filename) {
      include $filename;
   }
});

$app->run();
