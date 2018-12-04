<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models as m;
use \wishlist\vues as v;

class ContItem {

  private $db;

  public function __construct(){
    //$this->db = $connexion;
  }


  public function afficherItem($id){
    $i = \wishlist\models\Item::where("id","=",$id)->first();
    $v = new v\VueContItem($i, 'AFFICHAGE');
    $v->render();

  }
  // methode pour ajouter un item a la base
  public function ajouterItem($nom, $desc){
    $i = new \wishlist\models\Item();
    $i->liste_id = 2;
    $i->nom = $nom;
    $i->descr = $desc;
    $i->save();
    echo 'ajout effectué<br>';
  }

  public function modifierItem($id, $modif){
    $i = \wishlist\models\Item::where("id","=",$id)->first();
    $i->liste_id = $modif['liste_id'];
    $i->nom = $modif['nom'];
    $i->descr = $modif['descr'];
    $i->save();
    echo 'modification effectué<br>';
  }

  public function supprimerItem($id){
    $i = \wishlist\models\Item::where("id","=",$id)->first();
    $i->delete();
    echo 'suppression effectué<br>';
  }


}


 ?>
