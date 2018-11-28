<?php

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models as m;

class ContItem {

  // methode pour la
  public function connexion(){
    $info = parse_ini_file('src/conf/conf.ini');
    $db = new DB();
    $db->addConnection($info);
    $db->setAsGlobal();
    $db->bootEloquent();
    return $db;
  }
  // methode pour ajouter un item a la base
  public function ajouterItem(){

  }

}


 ?>
