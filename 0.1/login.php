<?php

/**
 * An endpoint to create an account.
 * Will also log the account in.
 */
$app->post('/register/', function () {
   // TODO: Use Chris's fancy functions.
   $username = $_POST['username'];
   $password = $_POST['password'];
   $email = $_POST['email'];
});

/**
 * The login endpoint. Expects multipart form data
 * in the form of POST parameters.
 * Will return JSON representing the outcome of the
 * login attempt, and -- if successful -- will also return
 * the api key.
 */
$app->post('/login/', function () {
   $res = new APIResponse(['public']);
   $params = $res->params($_POST, ['username','password']);
   $user = User::login($params['username'], $params['password'], $api = true);

   if ($user) {
      $key = $user->key;
      $res->addData(['key' => $key]);
   } else {
      $res->error("There was a problem logging you in.");
   }

   $res->respond();
});

/** 
 * Some people still like to logout (like it's 1999)
 */
$app->post('/logout/', function () {

});
