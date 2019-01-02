<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models\Item;
use \wishlist\models\Liste;
use \wishlist\vues\VueParticipant;
use \wishlist\Auth\Authentification as Auth;


const LISTES = 1.0;
const LISTE_CREA = 2.0;
const LISTE_CO = 2.1;
const LISTE_INV = 2.2;
const ITEM = 3.0;
const RESERVER = 4.0;


class ContAffichageListe {

    public function __construct(){}
    
    
    public function afficherListes(){
        
        $listes = Liste::get();
        
        $vue = new VueParticipant(array('liste' => $listes));
        $vue->render(LISTES);
    }

    public function afficherListe($token){
        $listes = Liste::where('token', 'like', $token)->get();
        $vue = new VueParticipant(array('liste' => $listes));
        
        if(Auth::isCreator($token)){ // si l'utilisateur est créateur
            $vue->render(LISTE_CREA);
        } elseif(Auth::isLogged()){ // si l'utilisateur est connecté
            $vue->render(LISTE_CO);
        } else{ // si l'utilisateur n'est pas connecté (vue invité)
            $vue->render(LISTE_INV);
        }
    }
    
    public function afficherListeInvite($share){
        $listes = Liste::where('share', 'like', $share)->get();
        $vue = new VueParticipant(array('liste' => $listes));
        
        $vue->render(LISTE_INV);
    }
    
    public function afficherItemListe($id) {
        
        $item = Item::where('id', '=', $id)->first();
        
        $vue = new VueParticipant(array('item' => $item));
        $vue->render(ITEM);
    }
    
    public function afficherReservationItem($id){
        
        $item = Item::where('id', '=', $id)->first();
        
        $vue = new VueParticipant(array('item' => $item));
        $vue->render(RESERVER);
    }
    
    public function reserverItem($id){
        $app = \Slim\Slim::getInstance();
        $app->response->redirect($app->urlFor('itemListe', array('id' => $id)));
    }

}


 ?>
