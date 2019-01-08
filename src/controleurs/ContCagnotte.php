<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models\Item;
use \wishlist\models\Liste;
use \wishlist\models\Reservation;
use \wishlist\models\Membre;
use \wishlist\models\Cagnotte;
use \wishlist\models\Participation;
use \wishlist\vues\VueAffichageListe;
use \wishlist\Auth\Authentification as Auth;


class ContCagnotte {
    
    public function __construct(){}
    
    public function creerCagnotte(){
        $userId = Auth::getIdUser();
        
        if(isset($userId) and isset($_POST['idListe']) and isset($_POST['idItem'])){
            $ca = new Cagnotte();
            $ca->idListe = $_POST['idListe'];
            $ca->idItem = $_POST['idItem'];
            $ca->save();
            
            $token = Liste::where('no', '=', $_POST['idListe'])->first()->token;

            $app = new \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('listeCrea', array('token' => $token)));
        } else{
            // afficher un message d'avertissement
        }
    }
    
    public function participerCagnotte(){
        $userId = Auth::getIdUser();
        
        if(isset($userId) and isset($_POST['idCagnotte']) and isset($_POST['nom'] and isset($_POST['prénom']) and isset($_POST['montant']))){
            $part = new Participation();
            $part->idCagnotte = $_POST['idCagnotte'];
            $part->idUser = $userId;
            $part->nom = $_POST['nom'];
            $part->prenom = $_POST['prénom'];
            $part->montant = $_POST['montant'];
            
            if(isset($_POST['message'])){
                $part->message = $_POST['message'];
            }
            
            $part->save();
            
            $share = Liste::where('no', '=', $_POST['idListe'])->first()->share;

            $app = new \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('listeShare', array('token' => $share)));
        } else{
            // afficher un message d'avertissement
        }
    }
}