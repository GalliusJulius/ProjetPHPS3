<?php
namespace wishlist\models;
use \Illuminate\Database\Eloquent\Model as Model;

class Membre extends Model{
    protected $table='membres';
    protected $primaryKey = 'email';
    public $timestamps = false;
    public $incrementing = false;
    // A changer (à supprimer) : par un id
    protected $fillable = ['email'];
    

    public function listes() {
        return $this->hasMany('\wishlist\models\Liste','user_id');
    }
    
    public function reservations() {
        return $this->hasMany('\wishlist\models\Reservation','idUser');
    }
}