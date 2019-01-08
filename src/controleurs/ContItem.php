<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \wishlist\models as m;
use \wishlist\vues as v;
use \wishlist\Auth\Authentification as Auth;

const LISTES = 1.0;

class ContItem {

  public function modifier($token, $id){
    $liste =  m\Liste::where("token","=",$token)->first();
    $item = m\Item::where("id","=",$id)->first();
    $v = new v\VueAffichageListe(array('liste'=> $liste, 'item' => $item));
    $v->render('MODIFIER');

  }

  public function modifierItem($token, $id){
    $liste =  m\Liste::where("token","=",$token)->first();
    $item = m\Item::where("id","=", $id)->first();
    $item->nom = $_POST['nom'];
    $item->descr = $_POST['description'];
    $item->tarif = $_POST['tarif'];
    $item->url = $_POST['url'];
    $item->save();

    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('listeCrea',array('token' => $token)));
  }

  // methode pour ajouter un item a la base
  public function ajouterItem($token){
    $liste =  m\Liste::where("token","=",$token)->first();
    $v = new v\VueAffichageListe($liste);
    $v->render('ITEM_AJOUT');
  }

  public function ajouter_item($token, $ajouter_item){
    $i = new m\Item();
    $l = m\Liste::where("token","=",$token)->first();
    $i->liste_id = $l->no;
    $i->nom = $_POST['nom'];
    $i->descr = $_POST['description'];
    $i->tarif = $_POST['tarif'];
    if(isset($_POST['url'])){
      $i->url = $_POST['url'];
    }
    $i->save();
    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('listeCrea',array('token' => $token)));
  }

  public function supprimerItem($id){
    $i = \wishlist\models\Item::where("id","=",$id)->first();
    $i->delete();
  }


}


 ?>
