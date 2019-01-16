<?php
namespace wishlist\models;
use \Illuminate\Database\Eloquent\Model as Model;
use \wishlist\models as m;

class Membre extends Model{
    protected $table = 'membres';
    protected $primaryKey = 'idUser';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = ['email'];
                            
   public function liste() {
       return $this->belongsToMany('\wishlist\models\Liste','liste_membres','membres_id','liste_no');
   }

    public function listes() {
        return $this->hasMany('\wishlist\models\Liste','user_id');
    }
    
    public function reservations() {
        return $this->hasMany('\wishlist\models\Reservation','idUser');
    }
    
}