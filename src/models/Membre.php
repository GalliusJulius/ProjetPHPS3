<?php
namespace wishlist\models;
use \Illuminate\Database\Eloquent\Model as Model;

class Membre extends Model{
    
    protected $table='membres';
    protected $primaryKey = 'email';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = ['email'];
}