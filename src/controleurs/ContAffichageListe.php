<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models\Item as Item;
use \wishlist\models\Liste as Liste;
use \wishlist\vues\VueParticipant as VueParticipant;

class ContAffichageListe {

    public function __construct(){}
    
    
    public function afficherListes(){
        
        $listes = Liste::get();
        
        $vue = new VueParticipant(array('liste' => $listes));
        $vue->render(1);
    }

    public function afficherListe($token){
        
        $listes = Liste::where('token', 'like', $token)->get();
        
        $vue = new VueParticipant(array('liste' => $listes));
        $vue->render(2);
    }
    
    public function afficherItemListe($id) {
        
        $item = Item::where('id', '=', $id)->first();
        
        $vue = new VueParticipant(array('item' => $item));
        $vue->render(3);
    }

}


 ?>
