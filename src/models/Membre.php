<?php
namespace wishlist\models;
use \Illuminate\Database\Eloquent\Model as Model;

class Membre extends Model{
    protected $table='Membres';
    protected $primaryKey = 'idUser';
    public $timestamps = false;
    

    public function listes() {
        return $this->hasMany('\wishlist\models\Liste','user_id');
    }
    
    public function reservations() {
        return $this->hasMany('\wishlist\models\Reservation','idUser');
    }
}