<?php

namespace wishlist\models;

use \Illuminate\Database\Eloquent\Model as Model;

class Particiaption extends Model{

  protected $table = 'participation';
  protected $primaryKey = 'idParticip';
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
    
  public function cagnotte(){
    return $this->belongsTo('\wishlist\models\Cagnotte','idCagnotte');
  }
}
