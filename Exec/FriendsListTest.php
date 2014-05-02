<?php

include __DIR__ . "/Essential.php";

//DB::debugMode();

function printRoster($list) {
   $roster = $list->roster();
   print("\n---------------\n");
   print("ROSTER FOR {$list->userid}\n");
   print(vsprintf("%s %s \t %s", ['a','b','status']) . "\n");
   print("---------------\n");
   foreach($roster as $fr) {
      $row = vsprintf("%d %d \t %s", [$fr['usera'], $fr['userb'], $fr['status']]);
      print("$row\n");
   }
   print("---------------\n\n");
}

$rosterA = new FriendsList(1);
$rosterB = new FriendsList(2);

if ($rosterB->requestFrom(1)) {
   print("There is a pending request from 1\n");
}

$rosterA->addFriend(2);
printRoster($rosterA);
printRoster($rosterB);

if ($rosterB->requestFrom(1)) {
   print("There is a pending request from 1\n");
}

print("accepting\n");
$rosterB->addFriend(1);
printRoster($rosterA);
printRoster($rosterB);

$rosterA->destroyRoster();
$rosterB->destroyRoster();
