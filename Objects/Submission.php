<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Submission extends Eloquent {

   protected $table = 'game_submissions';

   public function game() {
      return $this->belongsTo('Game', 'gameid', 'gameid');
   }

}
