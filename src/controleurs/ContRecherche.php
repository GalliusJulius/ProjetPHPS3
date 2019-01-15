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
            
            if(! filter_var($_GET['search'], FILTER_SANITIZE_SPECIAL_CHARS)){
                throw new ExceptionPerso('La valeur entrée n\'est pas valide !', 'avert');
            } else{
                $search = filter_var($_GET['search'], FILTER_SANITIZE_SPECIAL_CHARS);
            }
            
            $userId = Auth::getIdUser();
            $l1 = Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->where("titre", "like", "%" . $search . "%")->get();
            $l2 =  Liste::where('public', '=', '1')->where("user_id","!=",$userId)->where("titre", "like", "%" . $search . "%")->get();
            $listes = $l1->merge($l2);
            
            $membres = Membre::select('nom', 'prenom', 'idUser')->where("idUser", "!=", $userId)->where(function($q) use($userId){
                $q->where("prenom", "like", "%" . $search . "%")->orwhere("nom", "like", "%" . $search . "%");
            })->get();
            
            $vue = new VueWebSite(array("liste" => $listes, "membre" => $membres, "recherche" => $_GET));
            $vue->render('RECHERCHE');
            
        } else{
            $_SESSION['messageErreur'] = "Oups ! Un problème est survenu !";
            $_SESSION['typeErreur'] = "avert";
            
            $vue = new VueWebSite(array("liste" => NULL, "membre" => NULL, "recherche" => NULL));
            $vue->render('RECHERCHE');
        }
    }
    
    public function rechercherAvancee(){
        try{
            
            
            if(isset($_GET['search'])){
                $listes = NULL;
                $membres = NULL;

                if(! filter_var($_GET['search'], FILTER_SANITIZE_SPECIAL_CHARS)){
                    throw new ExceptionPerso('La valeur du mot clé entrée n\'est pas valide !', 'avert');
                } else{
                    $search = filter_var($_GET['search'], FILTER_SANITIZE_SPECIAL_CHARS);
                }

                if((isset($_GET['on'])) and (($_GET['on'] == "Les deux") or ($_GET['on'] == "Listes"))){

                    $userId = Auth::getIdUser();

                    if(isset($_GET['date'])){

                        if(! filter_var($_GET['date'], FILTER_SANITIZE_STRING)){
                            throw new ExceptionPerso('La valeur de la date entrée n\'est pas valide !', 'avert');
                        } else{
                            $date = filter_var($_GET['date'], FILTER_SANITIZE_STRING);
                        }

                        if(isset($_GET['deep']) and ($_GET['deep'] == "deep")){

                            $l1 = Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->where("expiration", ">=", $date)->where(function($q){
                                $q->where("description", "like", "%" . $search . "%")->orwhere("titre", "like", "%" . $search . "%");
                            })->get();

                            $l2 =  Liste::where('public', '=', '1')->where("user_id","!=",$userId)->where("expiration", ">=", $date)->where(function($q){
                                $q->where("description", "like", "%" . $search . "%")->orwhere("titre", "like", "%" . $search . "%");
                            })->get();

                            $listes = $l1->merge($l2);

                        } else{

                            $l1 = Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->where("titre", "like", "%" . $search . "%")->where("expiration", ">=", $date)->get();

                            $l2 =  Liste::where('public', '=', '1')->where("user_id","!=",$userId)->where("titre", "like", "%" . $search . "%")->where("expiration", ">=", $date)->get();

                            $listes = $l1->merge($l2);

                        }
                    } else{
                        if(isset($_GET['deep']) and ($_GET['deep'] == "deep")){

                            $l1 = Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->where(function($q){
                                $q->where("description", "like", "%" . $search . "%")->orwhere("titre", "like", "%" . $search . "%");
                            })->get();

                            $l2 =  Liste::where('public', '=', '1')->where("user_id","!=",$userId)->where(function($q){
                                $q->where("description", "like", "%" . $search . "%")->orwhere("titre", "like", "%" . $search . "%");
                            })->get();

                            $listes = $l1->merge($l2);

                        } else{

                            $l1 = Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->where("titre", "like", "%" . $search . "%")->get();

                            $l2 =  Liste::where('public', '=', '1')->where("user_id","!=",$userId)->where("titre", "like", "%" . $search . "%")->get();

                            $listes = $l1->merge($l2);

                        }
                    }

                    if(isset($_GET['nbReserv']) and ($_GET['nbReserv'] != '')){

                        if((! filter_var($_GET['nbReserv'], FILTER_SANITIZE_INT)) or (! is_numeric($_GET['nbReserv']))){
                            throw new ExceptionPerso('La valeur du nombre de réservation entrée n\'est pas valide !', 'avert');
                        }

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

                        if((! filter_var($_GET['nbItem'], FILTER_SANITIZE_INT)) or (! is_numeric($_GET['nbItem']))){
                            throw new ExceptionPerso('La valeur du nombre d\'items entrée n\'est pas valide !', 'avert');
                        }

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

                        $membres = Membre::select('nom', 'prenom', 'idUser')->where("idUser", "!=", $userId)->where(function($q) use($userId){
                            $q->where("prenom", "like", "%" . $search . "%")->orwhere("email", "like", "%" . $search . "%")->orwhere("Pseudo", "like", "%" . $search . "%")->orwhere("nom", "like", "%" . $search . "%");
                        })->get();

                    } else{

                        $membres = Membre::select('nom', 'prenom', 'idUser')->where("idUser", "!=", $userId)->where(function($q) use($userId){
                            $q->where("prenom", "like", "%" . $search . "%")->orwhere("nom", "like", "%" . $search . "%");
                        })->get();
                    }

                    if($_GET['on'] == "Créateurs"){
                        foreach($membres as $m){
                            $listeCount = Liste::where("user_id","=",$m->idUser)->get();
                            if(count($listeCount) == 0){
                                unset($m->idUser);
                                unset($m->nom);
                                unset($m->prenom);
                            }
                        }
                    }

                }

                $get = $_GET;
                $vue = new VueWebSite(array("liste" => $listes, "membre" => $membres, "recherche" => $get));
                $vue->render('RECHERCHE');

            } else{
                $_SESSION['messageErreur'] = "Oups ! Un problème est survenu !";
                $_SESSION['typeErreur'] = "avert";

                $vue = new VueWebSite(array("liste" => NULL, "membre" => NULL, "recherche" => NULL));
                $vue->render('RECHERCHE');
            }
            
            
        } catch(ExceptionPerso $e){
            $_SESSION['messageErreur'] = $e->getMessage();
            $_SESSION['typeErreur'] = $e->getType();
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('recherche'));
        }
        
    }
}