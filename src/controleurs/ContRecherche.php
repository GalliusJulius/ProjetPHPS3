<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models\Item;
use \wishlist\models\Liste;
use \wishlist\models\Reservation;
use \wishlist\models\Membre;
use \wishlist\models\Participation;
use \wishlist\vues\VueAffichageListe;
use \wishlist\Auth\Authentification as Auth;


class ContRecherche {
    
    public function __construct(){}
    
    public function afficherRecherche(){
        if(isset($_GET['search'])){
            $listes = Liste::where("titre", "like", "%" . $_GET['search'] . "%")->get();
            
            $userId = Auth::getIdUser();
            $membres = Membre::select('nom', 'prénom')->where("idUser", "!=", $userId)->where("nom", "like", "%" . $_GET['search'] . "%")->orwhere(function($q) use($userId){
                $q->where("idUser", "!=", $userId)->where("prénom", "like", "%" . $_GET['search'] . "%");
            })->get();
            
            $vue = new VueAffichageListe(array("liste" => $listes, "membre" => $membres, "recherche" => $_GET['search']));
            $vue->render('RECHERCHE');
            
        } else{
            // Afficher avertissement
        }
        
    }
    
    public function rechercherAvancee(){
        if(isset($_GET['search'])){
            $listes = Liste::where("titre", "like", "%" . $_GET['search'] . "%")->get();
            
            $userId = Auth::getIdUser();
            $membres = Membre::select('nom', 'prénom')->where("idUser", "!=", $userId)->where("nom", "like", "%" . $_GET['search'] . "%")->orwhere(function($q) use($userId){
                $q->where("idUser", "!=", $userId)->where("prénom", "like", "%" . $_GET['search'] . "%");
            })->get();
            
            $vue = new VueAffichageListe(array("liste" => $listes, "membre" => $membres, "recherche" => $_GET['search']));
            $vue->render('RECHERCHE');
            
        } else{
            // Afficher avertissement
        }
    }
}