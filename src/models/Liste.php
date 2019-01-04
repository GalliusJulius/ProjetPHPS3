<?php

namespace wishlist\models;

use \Illuminate\Database\Eloquent\Model as Model;

class Liste extends Model{

  protected $table = 'liste';
  protected $primaryKey = 'no';
  public $timestamps = false;


  public function items() {
    return $this->hasMany('\wishlist\models\Item','liste_id');
  }
    
    public function __toString(){
        return "<h2>$this->titre</h2> : <p>$this->description </p> <p> expire le : $this->expiration </p>"; 
    }
    
    public function membres() {
        return $this->belongsToMany('\wishlist\models\Membre','liste_membres','no','liste_no');
    }
}
