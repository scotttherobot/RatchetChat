<?php

require_once('../3P/php-beanstalk/src/Socket/Beanstalk.php');

$beanstalk = new Socket_Beanstalk();

$beanstalk->connect();

$beanstalk->put(23, 0, 500, "Hello, world!");

$beanstalk->disconnect();
