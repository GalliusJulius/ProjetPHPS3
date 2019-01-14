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
            
            $vue = new VueAffichageListe(array("liste" => $listes, "membre" => $membres, "recherche" => $_GET));
            $vue->render('RECHERCHE');
            
        } else{
            // Afficher avertissement
        }
    }
    
    public function rechercherAvancee(){
        // $_GET['on']  $_GET['date']  $_GET['deep']  $_GET['nbReserv']  $_GET['reserv']  $_GET['nbItem']  $_GET['item']  $_GET['nbReserv']
        
        // Vérifier à chaque fois si l'utilisateur est autorisé à voir les listes !
        // Faire la mise en page de la recherche
        // Ajouter les liens vers les listes / membres / créateurs
        
        if(isset($_GET['search'])){
            $listes = NULL;
            $membres = NULL;
            
            if((isset($_GET['on'])) and (($_GET['on'] == "Les deux") or ($_GET['on'] == "Listes"))){
                
                if(isset($_GET['date'])){
                    if(isset($_GET['deep']) and ($_GET['deep'] == "deep")){
                        $listes = Liste::where("titre", "like", "%" . $_GET['search'] . "%")->where("expiration", ">=", $_GET['date'])->orwhere(function($q){
                            $q->where("expiration", ">=", $_GET['date'])->where("description", "like", "%" . $_GET['search'] . "%");
                        })->get();
                    } else{
                        $listes = Liste::where("titre", "like", "%" . $_GET['search'] . "%")->where("expiration", ">=", $_GET['date'])->get();
                    }
                } else{
                    if(isset($_GET['deep']) and ($_GET['deep'] == "deep")){
                        $listes = Liste::where("titre", "like", "%" . $_GET['search'] . "%")->orwhere(function($q){
                            $q->where("description", "like", "%" . $_GET['search'] . "%");
                        })->get();
                    } else{
                        $listes = Liste::where("titre", "like", "%" . $_GET['search'] . "%")->get();
                    }
                }
                
                if(isset($_GET['nbReserv']) and ($_GET['nbReserv'] != '')){
                    if($_GET['reserv'] == "Minimum"){
                        foreach($listes as $l){
                            if(count($l->reservations()->get()) < $_GET['nbReserv']){
                                unset($l->no);
                                unset($l->user_id);
                                unset($l->titre);
                                unset($l->description);
                                unset($l->expiration);
                                unset($l->token);
                                unset($l->share);
                                unset($l->public);
                                unset($l->message);
                            }
                        }

                    } elseif($_GET['reserv'] == "Maximum"){
                        foreach($listes as $l){
                            if(count($l->reservations()->get()) > $_GET['nbReserv']){
                                unset($l->no);
                                unset($l->user_id);
                                unset($l->titre);
                                unset($l->description);
                                unset($l->expiration);
                                unset($l->token);
                                unset($l->share);
                                unset($l->public);
                                unset($l->message);
                            }
                        }

                    } elseif($_GET['reserv'] == "Exact"){
                        foreach($listes as $l){
                            if(count($l->reservations()->get()) != $_GET['nbReserv']){
                                unset($l->no);
                                unset($l->user_id);
                                unset($l->titre);
                                unset($l->description);
                                unset($l->expiration);
                                unset($l->token);
                                unset($l->share);
                                unset($l->public);
                                unset($l->message);
                            }
                        }

                    }
                }
                
                
                if(isset($_GET['nbItem']) and ($_GET['nbReserv'] != '')){
                    if($_GET['item'] == "Minimum"){
                        foreach($listes as $l){
                            if(count($l->items()->get()) < $_GET['nbItem']){
                                unset($l->no);
                                unset($l->user_id);
                                unset($l->titre);
                                unset($l->description);
                                unset($l->expiration);
                                unset($l->token);
                                unset($l->share);
                                unset($l->public);
                                unset($l->message);
                            }
                        }

                    } elseif($_GET['item'] == "Maximum"){
                        foreach($listes as $l){
                            if(count($l->items()->get()) > $_GET['nbItem']){
                                unset($l->no);
                                unset($l->user_id);
                                unset($l->titre);
                                unset($l->description);
                                unset($l->expiration);
                                unset($l->token);
                                unset($l->share);
                                unset($l->public);
                                unset($l->message);
                            }
                        }

                    } elseif($_GET['item'] == "Exact"){
                        foreach($listes as $l){
                            if(count($l->items()->get()) != $_GET['nbItem']){
                                unset($l->no);
                                unset($l->user_id);
                                unset($l->titre);
                                unset($l->description);
                                unset($l->expiration);
                                unset($l->token);
                                unset($l->share);
                                unset($l->public);
                                unset($l->message);
                            }
                        }

                    }
                }
                
            }
            
            
            if((isset($_GET['on'])) and (($_GET['on'] == "Les deux") or ($_GET['on'] == "Membres") or ($_GET['on'] == "Créateurs"))){
                
                
                $userId = Auth::getIdUser();
                
                if(isset($_GET['deep']) and ($_GET['deep'] == "deep")){
                    $membres = Membre::select('nom', 'prénom')->where("idUser", "!=", $userId)->where("nom", "like", "%" . $_GET['search'] . "%")->orwhere(function($q) use($userId){
                        $q->where("idUser", "!=", $userId)->where("prénom", "like", "%" . $_GET['search'] . "%");
                    })->get();
                } else{
                    $membres = Membre::select('nom', 'prénom')->where("idUser", "!=", $userId)->where("nom", "like", "%" . $_GET['search'] . "%")->orwhere(function($q) use($userId){
                        $q->where("idUser", "!=", $userId)->where("prénom", "like", "%" . $_GET['search'] . "%");
                    })->orwhere(function($q) use($userId){
                        $q->where("idUser", "!=", $userId)->where("email", "like", "%" . $_GET['search'] . "%");
                    })->orwhere(function($q) use($userId){
                        $q->where("idUser", "!=", $userId)->where("Pseudo", "like", "%" . $_GET['search'] . "%");
                    })->get();
                }
                
                if($_GET['on'] == "Créateurs"){
                    foreach($membres as $m){
                        if(count($m->listes()->get()) == 0){
                             unset($m);
                        }
                    }
                }
                
            }
            
            $get = $_GET;
            $vue = new VueAffichageListe(array("liste" => $listes, "membre" => $membres, "recherche" => $get));
            $vue->render('RECHERCHE');
            
        } else{
            // Afficher avertissement
        }
    }
}