<?php

namespace wishlist\models;

use \Illuminate\Database\Eloquent\Model as Model;

class Relation extends Model{

  protected $table = 'relation';
  protected $primaryKey = 'idUser1 idUser2';
  public $timestamps = false;
}
