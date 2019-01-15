<?php
namespace wishlist\models;
use \Illuminate\Database\Eloquent\Model as Model;
use \wishlist\models as m;

class Amis extends Model{
    
    protected $table='amis';
    protected $primaryKey = 'idDemande_idRecu';
    public $timestamps = false;
    
}