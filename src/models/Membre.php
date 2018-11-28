<?php
namespace wishlist\models;
use \Illuminate\Database\Eloquent\Model as Model;

class Membre extends Model{
    protected $table='Membres';
    protected $primaryKey = 'email';
    public $timestamps = false;
}