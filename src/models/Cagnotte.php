<?php
namespace wishlist\models;
use \Illuminate\Database\Eloquent\Model as Model;

class Cagnotte extends Model{
    protected $table='cagnotte';
    protected $primaryKey = 'idCagnotte';
    public $timestamps = false;
    

    public function listes() {
        return $this->hasMany('\wishlist\models\Liste','user_id');
    }
    
    public function participations() {
        return $this->hasMany('\wishlist\models\Particiaption','idCagnotte');
    }
}