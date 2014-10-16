<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Game extends Eloquent {

   public function submissions() {
      return $this->hasMany('Submission', 'gameid', 'gameid');
   }

   public function participants() {
      return $this->hasMany('Participant', 'gameid', 'gameid');
   }

   public function user() {
      return $this->belongsTo('EUser', 'userid', 'userid');
   }

   protected function getDateFormat() {
      return 'U';
   }
}
