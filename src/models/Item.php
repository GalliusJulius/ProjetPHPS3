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
    return Reservation::where('idItem', '=', $this->id)->first();
  }
    
  public function participationPossible() {
      $part = Participation::where('idItem', '=', $this->id)->get();
      $cpt = 0;
      foreach($part as $p){
          $cpt += $p->montant;
      }
          
      return ($cpt < $this->tarif);
  }

}
