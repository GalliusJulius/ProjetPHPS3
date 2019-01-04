<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models\Item;
use \wishlist\models\Liste;
use \wishlist\models\Reservation;
use \wishlist\models\Membre;
use \wishlist\vues\VueAffichageListe;
use \wishlist\Auth\Authentification as Auth;


const LISTES = 1.0;
const LISTES_CREA = 1.1;
const LISTE_CREA = 2.0;
const LISTE_CO = 2.1;
const LISTE_INV = 2.2;
const ITEM = 3.0;
const RESERVER = 4.0;


class ContAffichageListe {

    public function __construct(){}
    
    
    public function afficherListesPublic(){
        $listes = Liste::where('public', '=', '1')->get();
        
        $vue = new VueAffichageListe(array('liste' => $listes));
        $vue->render(LISTES);
    }
    
    public function afficherListesUtilisateurs(){
        
        $userId = Auth::getIdUser();
        
        if(isset($userId)){
            $m = Membre::where('idUser', '=', $userId)->first();
            $listes = $m->listes()->get();
            
            $vue = new VueAffichageListe(array('liste' => $listes));
            $vue->render(LISTES_CREA);
        } else{
            $app = \Slim\Slim::getInstance();
            $app->response->redirect($app->urlFor('listePublic'));
        }
    }

    public function afficherListe($token){
        $liste = Liste::where('token', 'like', $token)->first();
        $vue = new VueAffichageListe(array('liste' => $liste));
        
        if(Auth::isCreator($token)){ // si l'utilisateur est créateur
            $vue->render(LISTE_CREA);
        } else{ // sinon redirection vers l'affichage des invités
            $app = \Slim\Slim::getInstance();
            $app->response->redirect($app->urlFor('listeShare', array('share' => $liste->share)));
        }
    }
    
    public function afficherListeInvite($share){
        $listes = Liste::where('share', 'like', $share)->first();
        $vue = new VueAffichageListe(array('liste' => $listes));
        
        if(Auth::isLogged()){ // si l'utilisateur est connecté
            $vue->render(LISTE_CO);
        } else{ // si l'utilisateur n'est pas connecté (vue invité)
            $vue->render(LISTE_INV);
        }
    }
    
    public function afficherItemListe($id) {
        
        $item = Item::where('id', '=', $id)->first();
        
        $vue = new VueAffichageListe(array('item' => $item));
        $vue->render(ITEM);
    }
    
    public function afficherReservationItem($id){
        
        $item = Item::where('id', '=', $id)->first();
        
        $vue = new VueAffichageListe(array('item' => $item));
        $vue->render(RESERVER);
    }
    
    public function reserverItem($share, $idItem){
        $app = \Slim\Slim::getInstance();
        
        if(isset($_POST["nom"]) and isset($_POST["nom"]) and isset($_POST["message"])){
            $r = new Reservation();
            $r->prénom = $_POST["prénom"];
            $r->nom = $_POST["nom"];
            $r->message = $_POST["message"];
            
            $l = Liste::where('share', 'like', $share)->first();
            $idListe = $l->no;
            
            $r->idListe = $idListe;
            $r->idItem = $idItem;
            
            if(isset($_SESSION["idUser"])){
                $r->idUser = $_SESSION["idUser"];
            }
            
            $r->save();
        }
        
        $app->response->redirect($app->urlFor('listeShare', array('share' => $share)));
    }

}


 ?>
