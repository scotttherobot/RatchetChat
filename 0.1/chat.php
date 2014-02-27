<?php

$app->get('/threads/', function () {
   $res = new APIResponse(['user']);
   $res->addData(['threads' => Thread::myThreads($res->userid)]);
   $res->respond();
});

$app->get('/threads/:id/', function ($id) {
   $res = new APIResponse(['user']);
   $thread = new Thread($id, $res->userid);

   $meta = $thread->meta();

   if ($meta) {
      $since = idx($_GET, 'since', 0);
      $res->addData($meta);
      $res->addData([
         'transcript' => $thread->newSince($since),
      ]);
   } else {
      $res->error("This thread either does not exist, or you are not part of it.");
   }
   $res->respond();
});

$app->post('/threads/:id/', function ($id) {
   $res = new APIResponse(['user']);
   $thread = new Thread($id, $res->userid);

   $meta = $thread->meta();
   if ($meta) {
      $messageId = $thread->sendMessage($_POST);
      $res->addData(['messageId' => $messageId]);
   } else {
      $res->error("This thread either does not exist, or you are not a part of it.");
   }
   $res->respond();
});

$app->post('/notificationregister/', function () {
   $res = new APIResponse(['user']);

   $res->respond();
});
