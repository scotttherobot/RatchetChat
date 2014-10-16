<?php
// Autoload dependencies.
include '../vendor/autoload.php';

// And our libraries
foreach (glob("../Libs/*.php") as $filename) {
   include $filename;
}
// And our objects
foreach (glob("../Objects/*.php") as $filename) {
   include $filename;
}

// This is the key for Hater
$gcmApiKey = "AIzaSyA8_Rt7vvplk3gR8YrQwPjKJ-5QAyGvj8U";

$device = "APA91bGTfxHzLadYtEDr5N9Eaw2PlhgWugs1iVQb2mFG6CoHcDMIrXcyMjikP2tjHyy4973Pqbd_TDkTDqPmTvPAAWF34G4Wivf71_-s8lznHudYxleI657LwbrYBQPejZXBAbg6kaOs3tlxpZs1lTb5nii3uMujzwE2Kt7DcADBoHdYy2d7Tpw";

   $messageData = [
      'title' => "Hello",
      'message' => "This is a test",
   ];

   $response = PushLib::sendNotification(
      $gcmApiKey,
      [$device],
      $messageData);
   print_r($response);
   print("\n");

