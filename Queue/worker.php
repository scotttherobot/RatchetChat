<?php
// And our libraries
foreach (glob("../Libs/*.php") as $filename) {
   include $filename;
}
// And our objects
foreach (glob("../Objects/*.php") as $filename) {
   include $filename;
}

require_once('../3P/php-beanstalk/src/Socket/Beanstalk.php');

DB::$user = 'root';
DB::$password = 'anncoulter';
DB::$dbName = 'chat';
DB::$host = 'localhost';

$gcmApiKey = "AIzaSyCmsCJ334CHytuFIOW97DXLGpDs_G0jueQ";

$beanstalk = new Socket_Beanstalk();
$beanstalk->connect();

while (true) {
   $job = $beanstalk->reserve();
   $data = json_decode($job['body']);

   $subs = DB::query("
      SELECT s.uuid, s.type, t.name
      FROM subscriptions s
      JOIN participants p USING (`userid`)
      JOIN threads t ON (p.`threadid` = t.`id`)
      WHERE s.notifications = 'ON'
       AND p.notifications = 'ON'
       AND p.status != 'LEFT'
       AND p.threadid = %i", $data->threadid);

   $registrationIds = [];
   foreach ($subs as $sub) {
      print($sub['uuid'] . "\n");
      $registrationIds[] = $sub['uuid'];
   }
   $messageData = [
      'message' => "New activity in thread " . $subs[0]['name'],
      'threadid' => $data->threadid,
   ];

   $response = PushLib::sendNotification(
      $gcmApiKey,
      $registrationIds,
      $messageData);
   print_r($response);
   print("\n");


   $beanstalk->delete($job['id']);
}

$beanstalk->disconnect();

