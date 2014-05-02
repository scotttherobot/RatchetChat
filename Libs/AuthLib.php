<?php

/**
 * A class to authenticate users.
 * Includes login functions to verify username/passwords, as well
 * as verify authentication via HTTP headers.
 *
 * By Scott Vanderlind.
 * August 2, 2013.
 */

class AuthLib {
   
   private static $cookieName = 'token';

   // This allows us to have "public" endpoints that
   // use the APIResponse library.
   public static function isPublic($userid) {
      return true;
   }

   public static function logout() {
      $key = idx($_COOKIE, self::$cookieName, NULL);
      // Check with SessionManager to see if it's right.
      if($key) {
         SessionManager::deleteSession($key);
      }
   }

   /**
    * Creates a new user. Hashes the provided password, etc.
    */
   public static function newUser($username, $password, $firstname, $lastname, $email) {
      $q_newUser = "
         INSERT INTO users
         SET `username` = %s,
         `pw_hash` = %s,
         `sign_up_date` = %i,
         `firstname` = %s,
         `lastname` = %s,
         `email` = %s";
      $date = strtotime("now");
      $pwHash = CryptLib::createHash($password);
      if (self::isFree($username)) {
         DB::query($q_newUser, $username, $pwHash, $date, $firstname, $lastname, $email);
         return DB::insertId();
      } else {
         return false;
      }
   }

   /**
    * Checks to see if a username is available.
    */
   public static function isFree($username) {
      $q = "
         SELECT count(*)
         FROM users
         WHERE `username` = %s";
      return !DB::queryFirstField($q, $username);
   }
}
