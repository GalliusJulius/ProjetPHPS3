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
    
	// fonction qui permet d'afficher les listes publiques mais aussi de faire le trie sur ces listes
    public function afficherListesPublic(){

        if(isset($_GET['trie']) and (in_array($_GET['trie'], ['auteur', 'date']))){
            if($_GET['trie'] == 'date'){
                $listes = m\Liste::where('public', '=', '1')->where('expiration', '>=', date("Y-m-d"))->orderBy('expiration')->get();
            } else{
                $listes = m\Liste::where('public', '=', '1')->where('expiration', '>=', date("Y-m-d"))->join('membres', function($join){$join->on('liste.user_id', '=', 'membres.idUser');})->orderBy('nom')->get();
            }
        } else{
            $listes = m\Liste::where('public', '=', '1')->where('expiration', '>=', date("Y-m-d"))->orderBy('expiration')->get();
        }


        $vue = new VueWebSite(array('liste' => $listes));
        $vue->render('LISTES');
    }

    // fonction qui affiches les listes crées par un utilisateur, si c'est impossible, la fonction redirige vers l'accueil
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
            $app->response->redirect($app->urlFor('accueil'));
        }
    }

	// fonction qui affiche les listes disponibles pour l'utilisateur connecté, sinon redirige vers les listes partagées 
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

	// fonction qui permet de vérifié si l'utilisateur à les droits pour modifier ses listes ou avoir plus de détails, sinon redirige vers les listes publiques
    public function demandeAcces($token){
        $liste = m\Liste::where('token', 'like', $token)->first();
        $app = \Slim\Slim::getInstance();

        if(Auth::isAuthorized($token)){ // si l'utilisateur est autorisé à accéder à cette liste
            $app->response->redirect($app->urlFor('listeCrea', array('token' => $liste->token)));
        } else{ // sinon redirection vers l'affichage des invités
            $app->response->redirect($app->urlFor('listeShare', array('share' => $liste->share)));
        }
    }

	// fonction qui affiche les listes sur lequels l'utilisateur a été invité 
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
	
	// fonction qui permet de réserver un item 
    public function reserverItem($share, $idItem){
        try{


            $app = \Slim\Slim::getInstance();

            if(isset($_POST["nom"]) and isset($_POST["prenom"]) and ($_POST["nom"] != '') and ($_POST["prenom"] != '')){

                if((! filter_var($_POST['nom'], FILTER_SANITIZE_STRING)) or (! filter_var($_POST['prenom'], FILTER_SANITIZE_STRING))){
                    throw new ExceptionPerso('Les valeurs entrées ne sont pas valides !', 'avert');
                } else{
                    $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
                    $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
                }

                $r = new m\Reservation();
                $r->prenom = $_POST["prenom"];
                $r->nom = $_POST["nom"];

                if(isset($_POST["message"]) and ($_POST["message"] != '')){
                    if(! filter_var($_POST['message'], FILTER_SANITIZE_STRING)){
                        throw new ExceptionPerso('Les valeurs entrées ne sont pas valides !', 'avert');
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

                $_SESSION['messageErreur'] = "L'item a bien été réservé !";
                $_SESSION['typeErreur'] = "info";
                $r->save();
                $app->response->redirect($app->urlFor('listeShare', array('share' => $share)));

            } else{
                throw new ExceptionPerso('Une erreur est survenue lors de la réservation de l\'item, veillez à bien remplir tous les champs !', 'err');
            }


        } catch(ExceptionPerso $e){
            $_SESSION['messageErreur'] = $e->getMessage();
            $_SESSION['typeErreur'] = $e->getType();
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('listeShare', array('share' => $share)));
        }




    }

	// fonction qui affiches les liste auxquels l'utilisateur peut accéder 
    public function afficherMesListes($err){

        try{

            if(Auth::isLogged()){

                $userId = Auth::getIdUser();
                
                // Liste de l'utilisateur (directement crée par lui)
                $lUserAv = m\Liste::where('user_id',"=",$userId)->where('expiration', '<', date("Y-m-d"))->orderBy('expiration')->get();
                
                $lUserAp = m\Liste::where('user_id',"=",$userId)->where('expiration', '>=', date("Y-m-d"))->orderBy('expiration')->get();
                
                // Liste partagée à l'utilisateur
                $lShareAv = m\Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->where('expiration', '<', date("Y-m-d"))->orderBy('expiration')->get();
                
                $lShareAp = m\Membre::where('email',"=",$_SESSION['profil']['Email'])->first()->liste()->where("user_id","!=",$userId)->where('expiration', '>=', date("Y-m-d"))->orderBy('expiration')->get();
                
                
                $lShare = array($lShareAv, $lShareAp);
                $lUser = array($lUserAv, $lUserAp);

                $vue = new VueWebSite(array("erreur" => $err, "liste" => $lUser, "listePartagee" => $lShare));
                $vue->render('MESLISTES');

            } else{
                $app = \Slim\Slim::getInstance();
                $app->redirect($app->urlfor('connexion'));
            }


        } catch(ExceptionPerso $e){
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('accueil'));
        }
    }

	// fonction qui permet de rediriger vers le formulaire de creation de liste 
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

	// fonction qui permet de supprimer un liste qui nous a été partagé 
    public function supprimerListeShare($token){
        try{
            if(Auth::isAuthorized($token)){
                $liste = m\Liste::where("token","=",$token)->first();
                m\Membre::where("email","=",$_SESSION['profil']['Email'])->first()->liste()->detach($liste);
            } else{
                throw new ExceptionPerso('Vous n\'êtes pas autorisé à supprimer cette liste !', 'avert');
            }
        } catch(ExceptionPerso $e){
            $_SESSION['messageErreur'] = $e->getMessage();
            $_SESSION['typeErreur'] = $e->getType();
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('demandeAcces', array('token' => $token)));
        }

    }

	// fonction qui permet d'ajouter un message à une liste 
    public function ajouterMessageListe($token) {
        try{

            if(Auth::isAuthorized($token)){

                if(isset($_POST['message_liste']) and ($_POST['message_liste'] != '')){

                    if(! filter_var($_POST['message_liste'], FILTER_SANITIZE_STRING)){
                        throw new ExceptionPerso('Les valeurs entrées ne sont pas valides !', 'avert');
                    } else{
                        $msg = filter_var($_POST['message_liste'], FILTER_SANITIZE_STRING);
                    }

                    $liste = m\Liste::where("token", "=", $token)->first();
                    $liste->message = $msg;
                    $liste->save();


                    $_SESSION['messageErreur'] = "Le message a bien été ajouté !";
                    $_SESSION['typeErreur'] = "info";
                    $app = \Slim\Slim::getInstance();
                    $app->redirect($app->urlFor('listeCrea', array('token' => $token)));
                } else{
                    throw new ExceptionPerso('Une erreur est survenue lors de l\'ajout du message, veillez à bien remplir tous les champs !', 'err');
                }

            } else{
                throw new ExceptionPerso('Vous n\'êtes pas autorisé à ajouter de message sur cette liste !', 'avert');
            }


        } catch(ExceptionPerso $e){
            $_SESSION['messageErreur'] = $e->getMessage();
            $_SESSION['typeErreur'] = $e->getType();
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('demandeAcces', array('token' => $token)));
        }
    }
}
