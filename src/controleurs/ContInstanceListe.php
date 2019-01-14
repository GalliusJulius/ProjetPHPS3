<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \wishlist\models as m;
use \wishlist\vues as v;
use \wishlist\Auth\Authentification as Auth;



class ContInstanceListe {

  public function creerListe(){
    $v = new v\VueAffichageListe();
    $v->render('CREER_LISTE');
  }

  public function creer_liste(){
    $app = \Slim\Slim::getInstance();
    $liste = new m\Liste();
    $token = openssl_random_pseudo_bytes(32);
    $token = bin2hex($token);
    $liste->token = $token;
    $share = openssl_random_pseudo_bytes(32);
    $share = bin2hex($share);
    $liste->share = $share;


    $titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
    $liste->titre = $titre;

    $descr = filter_var($_POST['descr'], FILTER_SANITIZE_STRING);
    $liste->description = $descr;

    if (!\DateTime::createFromFormat('d/m/Y', $_POST['date'])){
      $liste->expiration = $_POST['date'];
    }else{
      $app->redirect($app->urlFor('creerListe')); // voir pour les erreurs
    }

    $liste->user_id = Auth::getIdUser();
    $liste->public = 0;
    $liste->save();

    $app->redirect($app->urlFor('listeCrea',array('token' => $token)));
  }


  public function modifierListe($token){
    $liste =  m\Liste::where("token","=",$token)->first();
    $v = new v\VueAffichageListe(array('liste'=> $liste));
    $v->render('MODIFIER_LISTE');
  }

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
      if (!\DateTime::createFromFormat('d/m/Y', $_POST['date'])){
        $liste->expiration = $_POST['date'];
      }else{
        $app->redirect($app->urlFor('creerListe')); // voir pour les erreurs
      }
    }
    $liste->save();

    $app->redirect($app->urlFor('listeCrea',array('token' => $token)));
  }

  public function supprimer_liste($token){
    $app = \Slim\Slim::getInstance();
    $liste =  m\Liste::where("token","=",$token)->first();
    $liste->delete();

    $app->redirect($app->urlFor('mesListes'));
  }
}
