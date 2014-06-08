<?php
/**
 * A group of routes to maintain a friend list/roster for
 * the current user.
 * Scott Vanderlind, May 2014
 */


/**
 * Retrieves and returns the friend roster for a user.
 */
$app->get('/friends/', function () {
   $res = new APIResponse(['user']);
   $list = new FriendsList($res->userid);

   $res->addData([
      'roster' => $list->roster(),
   ]);

   $res->respond();
});

/**
 * Adds a friend by either making a friend request or 
 * confirming a pending request.
 */
$app->post('/friends/', function () {
   $res = new APIResponse(['user']);
   $params = $res->params($_POST, ['user']);
   $list = new FriendsList($res->userid);

   $added = $list->addFriend($params['user']);
   $res->addData(['added' => $added]);

   $res->respond();
});

/**
 * Deletes a friend from your roster.
 * Also serves to cancel a friend request.
 * Also serves to reject a friend request.
 */
$app->post('/friends/delete', function () use ($app) {
   $res = new APIResponse(['user']);
   $params = $res->params($_POST, ['user']);
   $list = new FriendsList($res->userid);

   $status = $list->removeFriend($params['user']);
   $res->addData(['removalStatus' => $status]);

   $res->respond();
});
