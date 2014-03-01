<?php

/**
 * Returns all the threads that the logged in user is a part of.
 */
$app->get('/threads/', function () {
   $res = new APIResponse(['user']);
   $res->addData(['threads' => Thread::myThreads($res->userid)]);
   $res->respond();
});

/**
 * Creates a new thread.
 */
$app->post('/threads/', function () {
   $res = new APIResponse(['user']);
   $params = $res->params($_POST, ['name']);

   $thread = Thread::newThread($params['name'], $res->userid);
   if ($thread) {
      $meta = $thread->meta();
      $res->addData($meta);
   } else {
      $res->error("The thread could not be created");
   }

   $res->respond();
});

/**
 * Returns the contents of a particular thread.
 * Use the ?since= paramater with a unix timestamp
 * to only return messages that have been added since
 * that time.
 */
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

/**
 * Posts a message into the thread.
 * POST parameters:
 *    body => the body text of a message.
 *    medid => the media id of a media item to attach to the message.
 * Both parameters are optional, but empty messages are kinda stupid, right?
 * It's up to the UI to prevent that though.
 */
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

/**
 * Leave a thread.
 * Removes the user from the thread for good. Doesn't delete their messages,
 * just revokes their read/write access and (implicitly) turns off notifications.
 */
$app->delete('/threads/:id/', function ($id) {
   $res = new APIResponse(['user']);
   $thread = new Thread($id, $res->userid);

   $meta = $thread->meta();
   if ($meta) {
      $thread->leave();
      $res->addData(['message' => "You are no longer in thread $id"]);
   } else {
      $res->error("This thread either does not exist, or you are not a part of it.");
   }
   $res->respond();
});

/**
 * Join a user into the thread.
 * POST params:
 *    userid => the user id of the user to add.
 */
$app->post('/threads/:id/join/', function ($id) {
   $res = new APIResponse(['user']);
   $thread = new Thread($id, $res->userid);

   $meta = $thread->meta();
   if ($meta) {
      $params = $res->params($_POST, ['userid']);
      $thread->addUser($params['userid']);
   } else {
      $res->error("This thread either does not exist, or you are not a part of it.");
   }
   $res->respond();
});


$app->post('/notificationregister/', function () {
   $res = new APIResponse(['user']);
   $params = $res->params($_POST, ['uuid', 'type']);

   if (PushLib::subscribe($res->userid, $params['type'], $params['uuid'])) {
      $res->addData([
         'message' => 'You were successfully subscribed.',
         'uuid' => $params['uuid'],
      ]);
   } else {
      $res->error("There was a problem subscribing.");
   }

   $res->respond();
});
