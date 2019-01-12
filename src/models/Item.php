<?php

namespace wishlist\models;

use \Illuminate\Database\Eloquent\Model as Model;

class Item extends Model{

  protected $table = 'item';
  protected $primaryKey = 'id';
  public $timestamps = false;

  public function liste() {
    return $this->belongsTo('\wishlist\models\Liste','liste_id');
  }
    
  public function reservation() {
    return $this->belongsTo('\wishlist\models\Reservation','idItem');
  }
    
  public function participations() {
    return $this->hasMany('\wishlist\models\Participation','idItem');
  }

}
