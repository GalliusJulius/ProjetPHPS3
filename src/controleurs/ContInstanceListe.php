<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \wishlist\models as m;
use \wishlist\vues as v;
use \wishlist\Auth\Authentification as Auth;



class ContInstanceListe {


  // fonction qui redirige vers le formulaire de création de liste 
  public function creerListe(){
    $v = new v\VueWebSite();
    $v->render('CREER_LISTE');
  }

  // fonction qui créer une liste avec un token d'indentification, un token de partage par défault et les valeurs dans le POST  
  public function creer_liste(){
    $app = \Slim\Slim::getInstance();
    $liste = new m\Liste();
    $token = openssl_random_pseudo_bytes(32);
    $token = bin2hex($token);
    $liste->token = $token;
    $share = openssl_random_pseudo_bytes(32);
    $share = bin2hex($share);
    $liste->share = $share;
    $erreur = array();

    $titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
    $liste->titre = $titre;

    $descr = filter_var($_POST['descr'], FILTER_SANITIZE_STRING);
    $liste->description = $descr;
      
    $date = new \DateTime($_POST['date']);
    $liste->expiration = $date;

    $liste->user_id = Auth::getIdUser();

    if(isset($_POST['liste_publique'])){
      $liste->public = $_POST['liste_publique'];
    }

    $liste->save();

    $app->redirect($app->urlFor('listeCrea',array('token' => $token)));
  }

 
  // fonction qui redirige vers le formulaire de modification de liste 
  public function modifierListe($token){
    $liste =  m\Liste::where("token","=",$token)->first();
    $v = new v\VueWebSite(array('liste'=> $liste));
    $v->render('MODIFIER_LISTE');
  }

  // fonction qui modifie la liste sélectionnée en fonction des valeurs dans le POST
  public function modifier_liste($token){
    $app = \Slim\Slim::getInstance();
    $liste =  m\Liste::where("token","=",$token)->first();

    if(isset($_POST['titre'])){
      $titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
      $liste->titre = $titre;
    }

    if(isset($_POST['descr'])){
      $descr = filter_var($_POST['descr'], FILTER_SANITIZE_STRING);
      $liste->description = $descr;
    }

     if(isset($_POST['date'])){
      $date = new \DateTime($_POST['date']);
      $liste->expiration = $date;
    }

    if(isset($_POST['liste_publique'])){
      $liste->public = $_POST['liste_publique'];
    }

    $liste->save();

    $app->redirect($app->urlFor('listeCrea',array('token' => $token)));
  }

  
  // fonction qui supprime la liste sélectionnée 
  public function supprimer_liste($token){
    $app = \Slim\Slim::getInstance();
    $liste =  m\Liste::where("token","=",$token)->first();
    $liste->delete();

    $app->redirect($app->urlFor('mesListes'));
  }
}
