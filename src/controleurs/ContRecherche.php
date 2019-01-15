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


class ContRecherche {
    
    public function __construct(){}
    
    private function delListe($l){
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
    
    public function afficherRecherche(){
        if(isset($_GET['search'])){
            $listes = Liste::where("titre", "like", "%" . $_GET['search'] . "%")->get();
            
            $userId = Auth::getIdUser();
            $membres = Membre::select('nom', 'prenom')->where("idUser", "!=", $userId)->where(function($q){
                $q->where("nom", "like", "%" . $_GET['search'] . "%")->orwhere("prenom", "like", "%" . $_GET['search'] . "%");
            })->get();
            
            $vue = new VueWebSite(array("liste" => $listes, "membre" => $membres, "recherche" => $_GET));
            $vue->render('RECHERCHE');
            
        } else{
            // Afficher avertissement
        }
    }
    
    public function rechercherAvancee(){
        // $_GET['on']  $_GET['date']  $_GET['deep']  $_GET['nbReserv']  $_GET['reserv']  $_GET['nbItem']  $_GET['item']  $_GET['nbReserv']
        
        // Faire la mise en page de la recherche
        // Faire fonctionner les liens vers membres / créateurs
        
        if(isset($_GET['search'])){
            $listes = NULL;
            $membres = NULL;
            
            if((isset($_GET['on'])) and (($_GET['on'] == "Les deux") or ($_GET['on'] == "Listes"))){
                
                $userId = Auth::getIdUser();
                
                if(isset($_GET['date'])){
                    if(isset($_GET['deep']) and ($_GET['deep'] == "deep")){
                        
                        $l1 = Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->where("expiration", ">=", $_GET['date'])->where(function($q){
                            $q->where("description", "like", "%" . $_GET['search'] . "%")->orwhere("titre", "like", "%" . $_GET['search'] . "%");
                        })->get();
                        
                        $l2 =  Liste::where('public', '=', '1')->where("user_id","!=",$userId)->where("expiration", ">=", $_GET['date'])->where(function($q){
                            $q->where("description", "like", "%" . $_GET['search'] . "%")->orwhere("titre", "like", "%" . $_GET['search'] . "%");
                        })->get();
                        
                        $listes = $l1->merge($l2);
                        
                    } else{
                        
                        $l1 = Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->where("titre", "like", "%" . $_GET['search'] . "%")->where("expiration", ">=", $_GET['date'])->get();
                        
                        $l2 =  Liste::where('public', '=', '1')->where("user_id","!=",$userId)->where("titre", "like", "%" . $_GET['search'] . "%")->where("expiration", ">=", $_GET['date'])->get();
                        
                        $listes = $l1->merge($l2);
                        
                    }
                } else{
                    if(isset($_GET['deep']) and ($_GET['deep'] == "deep")){
                        
                        $l1 = Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->where(function($q){
                            $q->where("description", "like", "%" . $_GET['search'] . "%")->orwhere("titre", "like", "%" . $_GET['search'] . "%");
                        })->get();
                        
                        $l2 =  Liste::where('public', '=', '1')->where("user_id","!=",$userId)->where(function($q){
                            $q->where("description", "like", "%" . $_GET['search'] . "%")->orwhere("titre", "like", "%" . $_GET['search'] . "%");
                        })->get();
                        
                        $listes = $l1->merge($l2);
                        
                    } else{
                        
                        $l1 = Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->where("titre", "like", "%" . $_GET['search'] . "%")->get();
                        
                        $l2 =  Liste::where('public', '=', '1')->where("user_id","!=",$userId)->where("titre", "like", "%" . $_GET['search'] . "%")->get();
                        
                        $listes = $l1->merge($l2);
                        
                    }
                }
                
                if(isset($_GET['nbReserv']) and ($_GET['nbReserv'] != '')){
                    if($_GET['reserv'] == "Minimum"){
                        foreach($listes as $l){
                            if(count($l->reservations()->get()) < $_GET['nbReserv']){
                                $this->delListe($l);
                            }
                        }

                    } elseif($_GET['reserv'] == "Maximum"){
                        foreach($listes as $l){
                            if(count($l->reservations()->get()) > $_GET['nbReserv']){
                                $this->delListe($l);
                            }
                        }

                    } elseif($_GET['reserv'] == "Exact"){
                        foreach($listes as $l){
                            if(count($l->reservations()->get()) != $_GET['nbReserv']){
                                $this->delListe($l);
                            }
                        }

                    }
                }
                
                
                if(isset($_GET['nbItem']) and ($_GET['nbReserv'] != '')){
                    if($_GET['item'] == "Minimum"){
                        foreach($listes as $l){
                            if(count($l->items()->get()) < $_GET['nbItem']){
                                $this->delListe($l);
                            }
                        }

                    } elseif($_GET['item'] == "Maximum"){
                        foreach($listes as $l){
                            if(count($l->items()->get()) > $_GET['nbItem']){
                                $this->delListe($l);
                            }
                        }

                    } elseif($_GET['item'] == "Exact"){
                        foreach($listes as $l){
                            if(count($l->items()->get()) != $_GET['nbItem']){
                                $this->delListe($l);
                            }
                        }

                    }
                }
                
            }
            
            
            if((isset($_GET['on'])) and (($_GET['on'] == "Les deux") or ($_GET['on'] == "Membres") or ($_GET['on'] == "Créateurs"))){
                
                
                $userId = Auth::getIdUser();
                
                if(isset($_GET['deep']) and ($_GET['deep'] == "deep")){
                    
                    $membres = Membre::select('nom', 'prenom')->where("idUser", "!=", $userId)->where(function($q) use($userId){
                        $q->where("prenom", "like", "%" . $_GET['search'] . "%")->orwhere("nom", "like", "%" . $_GET['search'] . "%");
                    })->get();
                    
                } else{
                    $membres = Membre::select('nom', 'prenom')->where("idUser", "!=", $userId)->where(function($q) use($userId){
                        $q->where("prenom", "like", "%" . $_GET['search'] . "%")->orwhere("email", "like", "%" . $_GET['search'] . "%")->orwhere("Pseudo", "like", "%" . $_GET['search'] . "%")->orwhere("nom", "like", "%" . $_GET['search'] . "%");
                    })->get();
                }
                
                if($_GET['on'] == "Créateurs"){
                    foreach($membres as $m){
                        $listeCount = Liste::where("user_id","=",$m->idUser)->get();
                        echo count($listeCount);
                        if(count($listeCount) == 0){
                            unset($m->id);
                            unset($m->nom);
                            unset($m->prenom);
                            unset($m->pseudo);
                            unset($m->email);
                            unset($m->mdp);
                            unset($m->comp);
                        }
                    }
                }
                
            }
            
            $get = $_GET;
            $vue = new VueWebSite(array("liste" => $listes, "membre" => $membres, "recherche" => $get));
            $vue->render('RECHERCHE');
            
        } else{
            // Afficher avertissement
        }
    }
}