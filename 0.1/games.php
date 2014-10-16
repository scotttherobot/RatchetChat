<?php

// Get all games that I am a part of, the ones that I own and the
// ones that I am participating in.
//
// {
// mine : [],
// participating : []
// }
$app->get('/games/', function () {
   $res = new APIResponse(['user']);
   // get the user's object
   $user = EUser::find($res->userid);
   
   $res->addData([
      'mygames' => $user->games,
      'participating' => $user->participations,
   ]);

   $res->respond();
});


// Create a new game.
$app->post('/games/', function () {
   $res = new APIResponse(['user']);
   $params = $res->params($_POST, ['title', 'challenge', 'participants']);
   // get the user's object
   $user = EUser::find($res->userid);

   // Create a new game object.
   // We will set its variables and then we will saVe it.
   $newGame = new Game();
   $newGame->userid = $res->userid;
   $newGame->title = $params['title'];
   $newGame->challenge = $params['challenge'];
   $newGame->status = 'OPEN';
   $newGame->starts = time();
   $newGame->starts = time() + strtotime("+1 day");
   $newGame->save();

   // Add the users.
   foreach ($params['participants'] as $userid) {
      // add $userid to the game
      $par = new Participant();
      // Do I need the gameid since it's relational?
      //$par->gameid = $newGame->gameid;
      $par->userid = $userid;
      // Inject it into the new game.
      $par->gameid = $newGame->id;
      $par->save();
   }

   $res->addData([
      'game' => $newGame,
   ]);

   $res->respond();

});
