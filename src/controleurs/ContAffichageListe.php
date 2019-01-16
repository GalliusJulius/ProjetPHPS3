<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models as m;
use \wishlist\vues\VueWebSite;
use \wishlist\controleurs\ExceptionPerso;
use \wishlist\Auth\Authentification as Auth;

class ContAffichageListe {

    public function __construct(){}

    public function afficherListesPublic($tri){
        switch($tri){
          case 'DATE':{
              $listes = m\Liste::where('public', '=', '1')->orderBy('expiration')->get();
              break;
          }
          case 'AUTEUR':{
              $listes = m\Liste::where('public', '=', '1')->orderBy('user_id')->get();
              break;
          }
        }

        $vue = new VueWebSite(array('liste' => $listes));
        $vue->render('LISTES');
    }
    
    // Inutile ???
    public function afficherListesUtilisateurs(){
        $listes = m\Liste::get();
        $userId = Auth::getIdUser();
        
        if(isset($userId)){
            $m = m\Membre::where('idUser', '=', $userId)->first();
            $listes = $m->listes()->get();
            
            $vue = new VueWebSite(array('liste' => $listes));
            $vue->render('LISTES_CREA');
        } else{
            $_SESSION['messageErreur'] = "Vous n'êtes pas autorisé à accéder à cette liste !";
            $_SESSION['typeErreur'] = "err";
            $app = \Slim\Slim::getInstance();
            $app->response->redirect($app->urlFor('listePublic'));
        }
    }

    public function afficherListe($token){
        $liste = m\Liste::where('token', 'like', $token)->first();
        $vue = new VueWebSite(array('liste' => $liste));
        
        $listes = m\Liste::where('token', 'like', $token)->get();
        
        if(Auth::isAuthorized($token)){ // si l'utilisateur est autorisé à accéder à cette liste
            $vue->render('LISTE_CREA');
        } else{ // sinon redirection vers l'affichage des invités
            $_SESSION['messageErreur'] = "Vous n'êtes pas autorisé à accéder à cette liste !";
            $_SESSION['typeErreur'] = "err";
            $app = \Slim\Slim::getInstance();
            $app->response->redirect($app->urlFor('listeShare', array('share' => $liste->share)));
        }
    }
    
    public function demandeAcces($token){
        $liste = m\Liste::where('token', 'like', $token)->first();
        $app = \Slim\Slim::getInstance();
        
        if(Auth::isAuthorized($token)){ // si l'utilisateur est autorisé à accéder à cette liste
            $app->response->redirect($app->urlFor('listeCrea', array('token' => $liste->token)));
        } else{ // sinon redirection vers l'affichage des invités
            $app->response->redirect($app->urlFor('listeShare', array('share' => $liste->share)));
        }
    }
    
    public function afficherListeInvite($share){
        
        $liste = m\Liste::where('share', 'like', $share)->first();
        
        $vue = new VueWebSite(array('liste' => $liste));
        
        // Voir si utile ? (la condition)
        if(Auth::isLogged()){ // si l'utilisateur est connecté
            $vue->render('LISTE_CO');
        } else{ // si l'utilisateur n'est pas connecté (vue invité)
            $vue->render('LISTE_INV');
        }
    }
    
    public function reserverItem($share, $idItem){
        try{
            
            
            $app = \Slim\Slim::getInstance();

            if(isset($_POST["nom"]) and isset($_POST["prenom"]) and ($_POST["nom"] != '') and ($_POST["prenom"] != '')){

                if((! filter_var($_POST['nom'], FILTER_SANITIZE_STRING)) or (! filter_var($_POST['prenom'], FILTER_SANITIZE_STRING))){
                    throw new ExceptionPerso('Les valeurs entrées ne sont pas valide !', 'avert');
                } else{
                    $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
                    $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
                }

                $r = new m\Reservation();
                $r->prenom = $_POST["prenom"];
                $r->nom = $_POST["nom"];

                if(isset($_POST["message"]) and ($_POST["message"] != '')){
                    if(! filter_var($_POST['message'], FILTER_SANITIZE_STRING)){
                        throw new ExceptionPerso('Les valeurs entrées ne sont pas valide !', 'avert');
                    } else{
                        $msg = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
                    }

                    $r->message = $msg;
                }
                $l = m\Liste::where('share', 'like', $share)->first();
                $idListe = $l->no;

                $r->idListe = $idListe;
                $r->idItem = $idItem;

                if(isset($_SESSION["idUser"])){
                    $r->idUser = $_SESSION["idUser"];
                }


                $r->save();
                $app->response->redirect($app->urlFor('listeShare', array('share' => $share)));
                
            } else{
                throw new ExceptionPerso('Une erreur est survenue lors de la réservation de l\'item, vérifez bien à remplir tout les champs !', 'err');
            }
            
            
        } catch(ExceptionPerso $e){
            $_SESSION['messageErreur'] = $e->getMessage();
            $_SESSION['typeErreur'] = $e->getType();
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('listeShare', array('share' => $share)));
        }
        
        
        
        
    }
    
    public function afficherMesListes($err){
        $userId = Auth::getIdUser();
        $fabrique = m\Liste::where('user_id',"=",$userId)->get();
        #on cherche les listes qui n'ont pas été crée par user mais a accès dessus
        $listes = m\Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->get();
        $fusion = array($fabrique,$listes);
        
        $vue = new VueWebSite(array("erreur" => $err, "liste" => $fusion, "listeParatagee" => $listes));
        $vue->render('MESLISTES');
    }
    
    public function ajouterListe($token){
        $erreur="";
        $verif=m\Liste::where("token","=",$token)->count();
        
        if($verif!=0){
            $verif2 = m\Membre::where("email","=",$_SESSION['profil']['Email'])->first()->liste()->where("token","=",$token)->count();
            $verif2+= m\Liste::where("token","=",$token)->where('user_id',"=",Auth::getIdUser())->count();
            if($verif2==0){
                $liste = m\Liste::where("token","=",$token)->first();
                m\Membre::where("email","=",$_SESSION['profil']['Email'])->first()->liste()->attach($liste->no);
                $erreur = "Ajouté";
            }
            else{
                $erreur = "Deja ajouté!";
            }
            
        }
        else{
            $erreur = "Liste inconnu";
        }
        return $erreur;
    }
    
    public function supprimerListeShare($token){
        $liste = m\Liste::where("token","=",$token)->first();
        m\Membre::where("email","=",$_SESSION['profil']['Email'])->first()->liste()->detach($liste);
    }

    public function afficherMessageListe($token) {
        $liste = m\Liste::where("token", "=", $token)->first();
        $liste->message = $_POST['message_liste'];
        $liste->save();
        
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('listeCrea', array('token' => $token)));
    }
}