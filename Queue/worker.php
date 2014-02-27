<?php

require_once('../3P/php-beanstalk/src/Socket/Beanstalk.php');

$beanstalk = new Socket_Beanstalk();

$beanstalk->connect();

while (true) {
   $job = $beanstalk->reserve();

   print("Job: ");
   print($job['body'] . "\n");

   $beanstalk->delete($job['id']);
}

$beanstalk->disconnect();
