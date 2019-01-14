<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models as m;
use \wishlist\vues\VueParticipant;
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
        $listes = m\Liste::where('public', '=', '1')->get();
        
        $vue = new VueAffichageListe(array('liste' => $listes));
        $vue->render(LISTES);
    }
    
    public function afficherListesUtilisateurs(){
        $listes = m\Liste::get();
        $userId = Auth::getIdUser();
        
        //if(isset($userId)){
            $m = m\Membre::where('idUser', '=', $userId)->first();
            $listes = $m->listes()->get();
            
            $vue = new VueAffichageListe(array('liste' => $listes));
            $vue->render(LISTES_CREA);
        /*} else{
            $app = \Slim\Slim::getInstance();
            $app->response->redirect($app->urlFor('listePublic'));
        }*/
    }

    public function afficherListe($token){
        $liste = m\Liste::where('token', 'like', $token)->first();
        $vue = new VueAffichageListe(array('liste' => $liste));
        $listes = m\Liste::where('token', 'like', $token)->get();
        //if(Auth::isCreator($token)){ // si l'utilisateur est créateur
        $vue->render(LISTE_CREA);
        /*} else{ // sinon redirection vers l'affichage des invités
            $app = \Slim\Slim::getInstance();
            $app->response->redirect($app->urlFor('listeShare', array('share' => $liste->share)));
        }*/
    }
    
    public function afficherListeInvite($share){
        $listes = m\Liste::where('share', 'like', $share)->first();
        $vue = new VueAffichageListe(array('liste' => $listes));      
        if(Auth::isLogged()){ // si l'utilisateur est connecté
            $vue->render(LISTE_CO);
        } else{ // si l'utilisateur n'est pas connecté (vue invité)
            $vue->render(LISTE_INV);
        }
    }
    
    public function reserverItem($share, $idItem){
        $app = \Slim\Slim::getInstance();
        
        if(isset($_POST["nom"]) and isset($_POST["prenom"])){
            $r = new m\Reservation();
            $r->prenom = $_POST["prenom"];
            $r->nom = $_POST["nom"];
            
            if(isset($_POST["message"]) and ($_POST["message"] != '')){
                $r->message = $_POST["message"];
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
            // Afficher un avertissement
        }
        
        
    }
    
    public function afficherMesListes($err){
        $userId = Auth::getIdUser();
        $fabrique = m\Liste::where('user_id',"=",$userId)->get();
        #on cherche les listes qui n'ont pas été crée par user mais a accès dessus
        $listes = m\Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->get();
        $fusion = array($fabrique,$listes);
        $vue = new \wishlist\vues\VueAccueil("mesListes",$err,$fusion);
        $vue->render();
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