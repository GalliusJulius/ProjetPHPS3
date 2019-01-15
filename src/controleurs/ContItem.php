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
    $v = new v\VueWebSite(array('liste'=> $liste, 'item' => $item));
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

    if(isset($_POST['nom'])){
      $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
      $item->nom = $nom;
    }

    if(isset($_POST['description'])){
      $descr = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
      $item->descr = $descr;
    }

    if(isset($_POST['url'])){
      $url = filter_var($_POST['url'], FILTER_SANITIZE_URL);
      $item->url = $url;
    }

    if(isset($_POST['tarif'])){
      $tarif = filter_var($_POST['tarif'], FILTER_SANITIZE_NUMBER_FLOAT);
      $item->tarif = $tarif;
    }

    if(isset($_POST['image_url']) and filter_var($_POST['image_url'], FILTER_VALIDATE_URL)) {
      $item->img = $_POST['image_url'];
    } elseif(isset($_FILES['image']['name'])) {
      $erreur = $this->copier_image($_FILES['image']['name']);
      $item->img = $_FILES['image']['name'];
    } else {
        $item->img = 'questionmark.png';
    }
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
  public function ajouterItem($token,$erreur){
    $liste =  m\Liste::where("token","=",$token)->first();
    $v = new v\VueWebSite(array('liste' => $liste, 'erreur' => $erreur));
    $v->render('ITEM_AJOUT');
  }

  public function ajouter_item($token, $ajouter_item){
    $i = new m\Item();
    $l = m\Liste::where("token","=",$token)->first();
    $i->liste_id = $l->no;

    if(isset($_POST['nom'])){
      $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
      $item->nom = $nom;
    }

    if(isset($_POST['description'])){
      $descr = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
      $item->descr = $descr;
    }

    if(isset($_POST['url'])){
      $url = filter_var($_POST['url'], FILTER_SANITIZE_URL);
      $item->url = $url;
    }

    if(isset($_POST['tarif'])){
      $tarif = filter_var($_POST['tarif'], FILTER_SANITIZE_NUMBER_FLOAT);
      $item->tarif = $tarif;
    }

    if(isset($_POST['image_url']) and filter_var($_POST['image_url'], FILTER_VALIDATE_URL)) {
      $i->img = $_POST['image_url'];
    } elseif(isset($_FILES['image']['name'])) {
      $erreur = $this->copier_image($_FILES['image']['name']);
      $i->img = $_FILES['image']['name'];
    } else {
        $i->img = 'questionmark.png';
    }
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
