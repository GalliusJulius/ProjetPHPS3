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
    
  public function copier_image($url) {
    $cptImage = m\Item::where("img", "=", $url)->count();
    $erreur = "";
    $maxsize = 10485760;
    if($cptImage == 0) {
     //on vérifie qu'il n'y a pas d'erreur
     if ($_FILES['image']['error'] > 0) {
      $erreur = "Erreur lors du transfert";
     } else {
       //on vérifie la taille du fichier
       if ($_FILES['image']['size'] > $maxsize) {
          $erreur = "Le fichier est trop gros";   
       } else {
          //on vérifie les dimensions du fichier
          $image_sizes = getimagesize($_FILES['image']['tmp_name']);
          if (($image_sizes[0] / $image_sizes[1] > 2) || ($image_sizes[0] / $image_sizes[1] < 0.5)) {
            $erreur = "L'image sera disproportionnée";
            } else {
              $nom = './src/img/' . $_FILES['image']['name'];
              move_uploaded_file($_FILES['image']['tmp_name'], $nom);    
            }
        }   
     }
    }
    return $erreur;
  }

  public function modifierItem($token, $id){
    $liste =  m\Liste::where("token","=",$token)->first();
    $item = m\Item::where("id","=", $id)->first();
    $item->nom = $_POST['nom'];
    $item->descr = $_POST['description'];
    $item->tarif = $_POST['tarif'];
    $item->url = $_POST['url'];
    $erreur = $this->copier_image($_FILES['image']['name']);
    $item->img = $_FILES['image']['name'];
    $item->save();

    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('listeCrea',array('token' => $token)));
  }
    
  public function supprimer_image($token, $id) {
      $liste = m\Liste::where("token", "=", $token)->first();
      $item = m\Item::where("id", "=", $id)->first();
      if(isset($_POST['supprimer_img'])) {
          $item->img = 'questionmark.png';
      }
      $item->save();
      $app = \Slim\Slim::getInstance();
      $app->redirect($app->urlFor('listeCrea', array('token' => $token)));
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
    $erreur = $this->copier_image($_FILES['image']['name']);
    $i->img = $_FILES['image']['name'];
    $i->save();
    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('listeCrea',array('token' => $token)));
  }
  
  public function supprimerItem($token, $id){
    $i = m\Item::where("id","=",$id)->first();
    $i->delete();

    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('listeCrea',array('token' => $token)));
  }
  
}


 ?>
