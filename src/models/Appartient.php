<?php
namespace wishlist\models;
use \Illuminate\Database\Eloquent\Model as Model;
use \wishlist\models as m;

class Appartient extends Model{
    
    protected $table='appartient';
    protected $primaryKey = ['membre','liste'];
    public $timestamps = false;
    
    
}