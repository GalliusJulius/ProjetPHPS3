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
use \wishlist\Auth\Authentification as Auth;


class ContCagnotte {
    
    public function __construct(){}
    
    public function creerCagnotte($id){
        $userId = Auth::getIdUser();
        $item = Item::where('id', '=', $id)->first();
        $token = Liste::where('no', '=', $item->liste_id)->first()->token;
        
        //if(Auth::isCreator($token) and isset($item)){
        $item->cagnotte = 1;
        $item->save();

        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('listeCrea', array('token' => $token)));
        //} else{
            // Message d'avertissement
        //}
        
    }
    
    public function participerCagnotte($id){
        
        $item = Item::where('id', '=', $id)->first();
        
        if(isset($item) and isset($_POST['nom']) and isset($_POST['prénom']) and isset($_POST['montant']) and is_numeric($_POST['montant'])){
            
            $verifPart = Participation::where('idItem', '=', $id)->get();
            $montantParticip = 0;
            
            foreach($verifPart as $var){
                $montantParticip += $var->montant;
            }
            
            if($montantParticip+$_POST['montant'] <= $item->tarif){
                $part = new Participation();
                $part->idItem = $id;
                $part->nom = $_POST['nom'];
                $part->prenom = $_POST['prénom'];
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
                // afficher un message d'avertissement
            }
            
        } else{
            // afficher un message d'avertissement
        }
    }
}