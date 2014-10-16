<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class EUser extends Eloquent {

   protected $table = 'users';
   protected $primaryKey = 'userid';
   protected $hidden = ['pw_hash', 'sign_up_date'];

   public function games() {
      return $this->hasMany('Game', 'userid');
   }

   public function participations() {
      return $this->hasMany('Participant', 'userid');
   }

   protected function getDateFormat() {
      return 'U';
   }

}
