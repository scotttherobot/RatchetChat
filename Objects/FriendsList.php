<?php

/**
 * A friend list roster object.
 * Intended to be used where relationships between two users
 * are needed.
 *
 * by Scott Vanderlind
 * May 2014
 */

class FriendsList {

   public $userid = false;

   /**
    * Create a new FriendsList object in the context
    * of the given userid.
    */
   function __construct($userid) {
      $this->userid = $userid;
   }

   /**
    * Get the user's friends roster.
    */
   public function roster() {
      return DB::query("
         SELECT *
         FROM friendships
         WHERE usera = %i
          OR userb = %i
         AND status = 'ACCEPTED'",
         $this->userid, $this->userid);
   }

   /**
    * Add a friend to the user's roster.
    * Makes a "friend request" if no request has been
    * made in the other direction.
    * Params:
    *    userid = the user to add
    *    force = skip the request/approve step.
    */
   public function addFriend($userid, $force = false) {
      // Check to see if there's a pending request.
      // If there is, "accept" it.
      if ($this->requestFrom($userid)) {
         $this->acceptRequest($userid);
         return $userid;
      }
      
      // If not, insert a new row.
      $status = $force ? 'ACCEPTED' : 'PENDING';
      DB::insert('friendships', [
         'usera' => $this->userid,
         'userb' => $userid,
         'status' => $status,
      ]);
   }

   /**
    * Accepts a pending friend request.
    * This assumes that there exists a request where
    * userb = $this->userid
    */
   private function acceptRequest($userid) {
      return DB::update('friendships', [
         'status' => 'ACCEPTED',
      ], "usera = %i AND userb = %i", $userid, $this->userid);
   }

   /**
    * Removes a friend from the friends list.
    * Just updates the relationship row to be 'removed'
    */
   public function removeFriend($userid) {
      return DB::update('friendships', [
         'status' => 'REMOVED',
      ], "(usera = %i AND userb = %i)
           OR (userb = %i AND usera = %i)",
      $this->userid, $userid, $userid, $this->userid);
   }

   /**
    * Returns all incoming requests for friendship.
    * These are relationships where $this->userid is the 
    * target user && status == 'pending'
    */
   public function requests() {
      return DB::query("
         SELECT *
         FROM friendships
         WHERE userb = %i
          AND status = 'PENDING'",
         $this->userid);
   }

   /**
    * Returns a list of unanswered requests.
    * These are relationships where $this->userid is the
    * initiator && status == 'pending'
    */
   public function unansweredRequests() {
      return DB::query("
         SELECT *
         FROM friendships
         WHERE usera = %i
          AND status = 'PENDING'", 
         $this->userid);
   }

   /**
    * Returns true if there is a pending request from a user.
    */
   public function requestFrom($userid) {
      return !!DB::queryFirstRow("
         SELECT *
         FROM friendships
         WHERE usera = %i
          AND userb = %i
          AND status = 'PENDING'",
         $userid, $this->userid);
   }

   /**
    * Returns true if $this->userid and the param user
    * are friends, that is:
    *    The relationshop row exists
    *    AND
    *    The relationship status == 'accepted'
    */
   public function isFriendsWith($userid) {
      return !!DB::queryFirstRow("
         SELECT *
         FROM friendships
         WHERE usera = %i
          AND userb = %i
          AND status = 'ACCEPTED'",
         $userid, $this->userid);
   }

   /**
    * DESTROYS ALL RELATIONSHIPS RELATED TO THIS USER.
    * THIS INCLUDES RELATIONSHIPS WHERE THE USER IS THE INITIATOR
    * AS WELL AS RELATIONSHIPS WHERE THE USER IS THE TARGET.
    * This is mostly for testing purposes.
    */
   public function destroyRoster() {
      return DB::delete('friendships',
         "usera=%i OR userb=%i",
         $this->userid, $this->userid);
   }
}
