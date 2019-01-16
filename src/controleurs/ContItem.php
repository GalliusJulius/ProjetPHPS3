<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \wishlist\models as m;
use \wishlist\vues as v;
use \wishlist\Auth\Authentification as Auth;

const LISTES = 1.0;

class ContItem {


  // fonction pour rediriger vers un formulaire de modification  
  public function modifier($token, $id){
    $liste =  m\Liste::where("token","=",$token)->first();
    $item = m\Item::where("id","=",$id)->first();
    $v = new v\VueWebSite(array('liste'=> $liste, 'item' => $item));
    $v->render('MODIFIER');

  }
  
	 
  // fonction qui copie l'image dans la base de donnée 
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

  
  // fonction qui modifie les éléments de l'item sélectionné en fonction des valeurs dans le POST
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
      $tarif = intVal($_POST['tarif']);
      $tarif = filter_var($tarif, FILTER_SANITIZE_NUMBER_INT);
      $item->tarif = $tarif;
    }

    if(isset($_POST['image_url']) and filter_var($_POST['image_url'], FILTER_VALIDATE_URL) and !empty($_POST['image_url'])) {
      $item->img = $_POST['image_url'];
    } elseif(isset($_FILES['image']['name']) and !empty($_FILES['image']['name'])) {
      $erreur = $this->copier_image($_FILES['image']['name']);
      $item->img = $_FILES['image']['name'];
    } else {
        $item->img = 'questionmark.png';
    }
    $item->save();

    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('listeCrea',array('token' => $token)));
  }

  
  // fonction qui supprime l'image sélectionné pour l'item en sélectionné
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

  
  // fonction qui redirige vers le formulaire pour ajouter un item a la liste sélectionnée
  public function ajouterItem($token){
    $liste =  m\Liste::where("token","=",$token)->first();
    $v = new v\VueWebSite(array('liste' => $liste));
    $v->render('ITEM_AJOUT');
  }

  
  // fonction qui ajoute un item en fonction des valeurs dans le POST pour la liste sélectionnée
  public function ajouter_item($token){
    $i = new m\Item();
    $l = m\Liste::where("token","=",$token)->first();
    $i->liste_id = $l->no;

    if(isset($_POST['nom'])){
      $n = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
      $i->nom = $n;
    }

    if(isset($_POST['description'])){
      $d = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
      $i->descr = $d;
    }

    if(isset($_POST['url'])){
      $u = filter_var($_POST['url'], FILTER_SANITIZE_URL);
      $i->url = $u;
    }

    if(isset($_POST['tarif'])){
      $t = intVal($_POST['tarif']);
      $t = filter_var($t, FILTER_SANITIZE_NUMBER_INT);
      $i->tarif = $t;
    }

    if(isset($_POST['image_url']) and filter_var($_POST['image_url'], FILTER_VALIDATE_URL) and !empty($_POST['image_url'])) {
      $i->img = $_POST['image_url'];
    } elseif(isset($_FILES['image']['name']) and !empty($_FILES['image']['name'])) {
      $erreur = $this->copier_image($_FILES['image']['name']);
      $i->img = $_FILES['image']['name'];
    } else {
        $i->img = 'questionmark.png';
    }
    $i->save();
      
    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('listeCrea',array('token' => $token)));
  }

  
  //fonction qui supprime le l'item sélectionné 
  public function supprimerItem($token, $id){
    $i = m\Item::where("id","=",$id)->first();
    $i->delete();

    $app = \Slim\Slim::getInstance();
    $app->redirect($app->urlFor('listeCrea',array('token' => $token)));
  }

}


 ?>
