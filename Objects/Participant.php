<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Participant extends Eloquent {

   protected $table = 'game_participants';
   protected $hidden = ['gameid', 'participantid', 'userid'];

   public function game() {
      return $this->belongsTo('Game', 'gameid', 'gameid');
   }

   public function user() {
      return $this->belongsTo('EUser', 'userid', 'userid');
   }

   protected function getDateFormat() {
      return 'U';
   }

}
