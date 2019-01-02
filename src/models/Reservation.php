<?php

namespace wishlist\models;

use \Illuminate\Database\Eloquent\Model as Model;

class Reservation extends Model{

  protected $table = 'reservation';
  protected $primaryKey = 'idReserv';
  public $timestamps = false;


  public function membre() {
    return $this->belongsTo('\wishlist\models\Membre','idUser');
  }
    
  public function liste() {
    return $this->belongsTo('\wishlist\models\Liste','idListe');
  }
    
  public function item() {
    return $this->belongsTo('\wishlist\models\Item','idItem');
  }
}
