<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models as m;

class ContItem {

  private $db;

  public function __construct($connexion){
    $this->db = $connexion;
  }

  // methode pour ajouter un item a la base
  public function ajouterItem($nom, $desc){
    $i = new \wishlist\models\Item();
    $i->liste_id = 2;
    $i->nom = $nom;
    $i->descr = $desc;
    $i->save();
  }

}


 ?>
