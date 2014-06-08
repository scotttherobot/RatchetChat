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
    * TODO: Limits and offsets
    */
   public function roster() {
      $rows = DB::query("
         SELECT *
         FROM friendships
         WHERE (usera = %i
          OR userb = %i)
         AND status = 'ACCEPTED'",
         $this->userid, $this->userid);

      // Take our weird data structure and map it
      // to something that's uniform.
      // TODO: investigate the efficiency of this.
      // I think it's O(n) right now :/
      $roster = [];
      foreach ($rows as $fr) {
         $friend = [];
         if ($fr['usera'] == $this->userid) {
            $friend['userid'] = $fr['userb'];
         } else {
            $friend['userid'] = $fr['usera'];
         }
         $friend['status'] = $fr['status'];
         $roster[] = array_merge($friend, User::meta($friend['userid']));
      }

      return $roster;
   }

   /**
    * Add a friend to the user's roster.
    * Makes a "friend request" if no request has been
    * made in the other direction.
    * Params:
    *    userid = the user to add
    *    force = skip the request/approve step.
    * Returns
    *    true if a request was made
    *    the user's id if a pending request was confirmed.
    */
   public function addFriend($userid, $force = false) {
      // Check to see if there's a pending request incoming.
      // If there is, "accept" it.
      if ($this->requestFrom($userid)) {
         $this->acceptRequest($userid);
         return $userid;
      }

      // If we're not already friends with this person, and we don't already
      // have an outstanding request TO them, then go ahead and add them.
      // TODO: See if there's a rejected request and if there is, deal.
      if (!$this->isFriendsWith($userid) && !$this->requestTo($userid)) {
         // If not, insert a new row.
         $status = $force ? 'ACCEPTED' : 'PENDING';
         DB::insert('friendships', [
            'usera' => $this->userid,
            'userb' => $userid,
            'status' => $status,
         ]);
         return $userid;
      }
      return false;
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
    * Just updates the relationship row to be 'removed',
    * but is conscious of requests. If there's a pending request,
    * it'll reject it.
    */
   public function removeFriend($userid) {
      $status = $this->requestFrom($userid) ? 'REJECTED' : 'REMOVED';
      DB::update('friendships', [
         'status' => $status,
      ], "(usera = %i AND userb = %i)
           OR (userb = %i AND usera = %i)",
      $this->userid, $userid, $userid, $this->userid);
      return $status;
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
    * Returns true if there is a pending request to a user.
    */
   public function requestTo($userid) {
      return !!DB::queryFirstRow("
         SELECT *
         FROM friendships
         WHERE usera = %i
          AND userb = %i
          AND status = 'PENDING'",
         $this->userid, $userid);
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
         WHERE (usera = %i
            AND userb = %i)
         OR (usera = %i
            AND userb = %i)
          AND status = 'ACCEPTED'",
         $userid, $this->userid, $this->userid, $userid);
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
