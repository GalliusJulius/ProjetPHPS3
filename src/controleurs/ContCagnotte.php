<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models\Item;
use \wishlist\models\Liste;
use \wishlist\models\Reservation;
use \wishlist\models\Membre;
use \wishlist\models\Participation;
use \wishlist\vues\VueWebSite;
use \wishlist\controleurs\ExceptionPerso;
use \wishlist\Auth\Authentification as Auth;


class ContCagnotte {
    
    public function __construct(){}
    
    public function creerCagnotte($id){
        try{
            
            $userId = Auth::getIdUser();
            $item = Item::where('id', '=', $id)->first();
            $token = Liste::where('no', '=', $item->liste_id)->first()->token;

            if(Auth::isCreator($token) and isset($item)){
                $item->cagnotte = 1;
                $item->save();


                $_SESSION['messageErreur'] = "Votre cagnotte a bien été crée !";
                $_SESSION['typeErreur'] = "info";
                $app = \Slim\Slim::getInstance();
                $app->redirect($app->urlFor('listeCrea', array('token' => $token)));
            } else{
                throw new ExceptionPerso('Vous n\'êtes pas autorisé à créer une cagnotte sur cet item !', 'avert');
            }
            
        } catch(ExceptionPerso){
            $_SESSION['messageErreur'] = $e->getMessage();
            $_SESSION['typeErreur'] = $e->getType();
            $share = Item::where('id', '=', $id)->first()->liste()->first()->share;
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('listeShare', array('share' => $share)));
        }
        
    }
    
    public function participerCagnotte($id){
        try{
            
            $item = Item::where('id', '=', $id)->first();
        
            if(isset($item) and isset($_POST['nom']) and isset($_POST['prenom']) and isset($_POST['montant']) and is_numeric($_POST['montant'])){
                
                if((! filter_var($_POST['nom'], FILTER_SANITIZE_STRING)) or (! filter_var($_POST['prenom'], FILTER_SANITIZE_STRING)) or (! filter_var($_POST['montant'], FILTER_SANITIZE_NUMBER_FLOAT)) or (! filter_var($_POST['message'], FILTER_SANITIZE_STRING))){
                    throw new ExceptionPerso('Les valeurs entrées ne sont pas valide !', 'avert');
                }
                
                $verifPart = Participation::where('idItem', '=', $id)->get();
                $montantParticip = 0;

                foreach($verifPart as $var){
                    $montantParticip += $var->montant;
                }

                if($montantParticip+$_POST['montant'] <= $item->tarif){
                    $part = new Participation();
                    $part->idItem = $id;
                    $part->nom = $_POST['nom'];
                    $part->prenom = $_POST['prenom'];
                    $part->montant = $_POST['montant'];

                    $userId = Auth::getIdUser();
                    if(isset($userId)){
                        $part->idUser = $userId;
                    }

                    if(isset($_POST['message'])){
                        $part->message = $_POST['message'];
                    }

                    $part->save();

                    $share = Liste::where('no', '=', $item->liste_id)->first()->share;

                    $app = \Slim\Slim::getInstance();
                    $app->redirect($app->urlFor('listeShare', array('share' => $share)));

                } else{
                    throw new ExceptionPerso('Le montant choisi est supérieur au montant restant à payer !', 'avert');
                }

            } else{
                throw new ExceptionPerso('Une erreur est survenue lors de la participation à l\'item, vérifez bien à remplir tout les champs !', 'err');
            }
            
        } catch(ExceptionPerso){
            $_SESSION['messageErreur'] = $e->getMessage();
            $_SESSION['typeErreur'] = $e->getType();
            $share = Item::where('id', '=', $id)->first()->liste()->first()->share;
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('listeShare', array('share' => $share)));
        }
        
    }
}