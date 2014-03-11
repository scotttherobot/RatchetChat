<?php

use Gaufrette\Filesystem;
use Gaufrette\File;
use Gaufrette\Adapter\Local as LocalAdapter;

$app->get('/media/', function () {
   
   $adapter = new LocalAdapter("media");
   $filesystem = new Filesystem($adapter);

   print_r($filesystem->keys());

});

$app->post('/media/', function () {

   $adapter = new LocalAdapter("media");
   $filesystem = new Filesystem($adapter);

   $tmpPath = $_FILES["file"]["tmp_name"];
   $name = $_FILES["file"]["name"];

   $file = new File($name, $filesystem);
   $image = file_get_contents($tmpPath);
   $file->setContent($image);

});
