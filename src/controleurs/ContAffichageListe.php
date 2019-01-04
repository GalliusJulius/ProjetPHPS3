<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models as m;
use \wishlist\vues\VueParticipant as VueParticipant;

class ContAffichageListe {

    public function __construct(){}
    
    
    public function afficherListes(){
        
        $listes = m\Liste::get();
        
        $vue = new VueParticipant(array('liste' => $listes));
        $vue->render(1);
    }

    public function afficherListe($token){
        
        $listes = m\Liste::where('token', 'like', $token)->get();
        
        $vue = new VueParticipant(array('liste' => $listes));
        $vue->render(2);
    }
    
    public function afficherItemListe($id) {
        
        $item = m\Item::where('id', '=', $id)->first();
        
        $vue = new VueParticipant(array('item' => $item));
        $vue->render(3);
    }
    
    public function afficherReservationItem($id){
        
        $item = m\Item::where('id', '=', $id)->first();
        
        $vue = new VueParticipant(array('item' => $item));
        $vue->render(4);
    }
    
    public function reserverItem($id){
        $app = \Slim\Slim::getInstance();
        $app->response->redirect($app->urlFor('itemListe', array('id' => $id)));
    }
    
    public function afficherMesListes(){
        session_start();
        $tab = m\Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->get();
        $vue = new \wishlist\vues\VueAccueil("mesListes","",$tab);
        $vue->render();
    }

}


 ?>
