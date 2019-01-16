<?php
namespace wishlist\controleurs;
use \wishlist\vues as v;
use \wishlist\models as m;
use \wishlist\Auth\Authentification as Auth;
use \wishlist\controleurs as c;

class ControleurCompte{
    
    protected $erreur = "";
    
	// fonction qui redirige vers la page de connection 
    public function recupererVue($type){
        #Si il accède à des fonctionnalités réservervés aux users connectés
        if(($type == "COMPTE" || $type =="CONTACT") && !Auth::isLogged()){
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('connexion'));
        }
        else{
            $v = new v\VueWebSite(array('erreur'=>$this->erreur));
            $v->render($type);
        }
    }
    
	// fonction qui connecte les utilisateurs 
    public function miseAjour(){
        if(!Auth::isLogged()){
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('connexion'));
        }
            else{
            $this->erreur="";
            $perso = m\Membre::where("email","=",$_SESSION['profil']['Email'])->first();
            if(isset($_POST['Nom']) && $_POST['Nom'] != ""){
               $perso->Nom= $_POST['Nom'];
            }
            if(isset($_POST['Prenom']) && $_POST['Prenom'] != ""){
               $perso->Prenom= $_POST['Prenom'];
            }
            if(isset($_POST['Pseudo']) && $_POST['Pseudo'] != ""  && filter_var($_POST['Pseudo'],FILTER_SANITIZE_STRING)){
               $perso->Pseudo= $_POST['Pseudo'];
            }
            if(isset($_POST['Message']) && $_POST['Message'] != ""  && filter_var($_POST['Pseudo'],FILTER_SANITIZE_STRING)){
               $perso->message= $_POST['Message'];
            }
            if(isset($_POST['Email']) && $_POST['Email'] != "" && filter_var($_POST['Email'],FILTER_VALIDATE_EMAIL)){
                if(m\Membre::where("email","=",$_POST['Email'])->count() == 0){
                    $perso->email=$_POST['Email'];
                    $_SESSION['profil']['Email'] = $_POST['Email'];
                }
                else{
                    $this->erreur= "Email déjà lié à un autre compte";
                }
            }
            if(isset($_POST['mdp']) && isset($_POST['mdpc']) && $_POST['mdp'] != "" && $_POST['mdpc'] != ""){
                if($_POST['mdp'] == $_POST['mdpc']){
                    $perso->mdp=password_hash($_POST['mdp'],PASSWORD_DEFAULT);
                }
                else{
                    $this->erreur= "Les mots de passe ne correspondent pas !";
                }
            }
            $perso->save();
        }
    }
    
	// fonction qui supprime un compte
    public function supprimerCompte(){
        $ligneSuppr = m\Membre::where("email","=",$_SESSION['profil']['Email'])->delete();
        $gest = new c\ControleurConnexion();
        $gest->seDeconnecter();
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('connexion'));
    }
    
	// fonction qui affiche les utilisateurs avec les listes qu'ils ont crées 
    public function afficherCreateurs(){
        $createur = m\Membre::get();
        $res = array();
        $i=0;
        foreach($createur as $val){
            $nb = m\Liste::where('user_id','=',$val->idUser)->count();
            if($nb!=0){
                $res[$i][]=$val;
                $res[$i][]=$nb;
                $i++;
            }
        }
        $v = new v\VueWebSite(array('membre'=>$res));
        $v->render("CREATEURS");
    }
     
	// fonction qui affiche les élements du compte connecté 
    public function afficherCompte($id){
        if(Auth::isLogged() && $id == $_SESSION['idUser']){
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('Compte'));
        }
        else{
            $user = m\Membre::where("idUser","=",$id)->first();
            $fabrique = m\Liste::where('user_id',"=",$id)->get();
            $amis = m\Amis::where(function($query) use($id){
                $query->where("idRecu","=",$id)->where("idDemande","=",Auth::getIdUser());
            })->orWhere(function($query) use ($id){
                $query->where("idDemande","=",$id)->where("idRecu","=",Auth::getIdUser());
            })->first();
            $arr=array();
            if(isset($user)){
                $arr['membre']=$user;
                $arr['amis']=$amis;
                $arr['liste']=$fabrique;
                $v = new v\VueWebSite($arr);
                $v->render("VISIONCOMPTES");
            }
            else{
                //on met erreur page not found
            }
        }
    }
    
	// fonction qui permet j'ajouter un amis 
    public function ajouterAmi($id){
        if(Auth::isLogged()){
            $ajout = new m\Amis();
            $ajout->idDemande=Auth::getIdUser();
            $ajout->idRecu=$id;
            $ajout->save();
        }
        else{
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('connexion'));
        }
    }
    
	// fonction qui affiche les contacts de l'utilisateur connecté 
    public function affichageContacts(){
        if(Auth::isLogged()){
            $liste = array();
            $attente = m\Amis::where("idRecu","=",Auth::getIdUser())->where("statut","=","Attente")->get();
            $amis = m\Amis::where(function($q){
                $q->where("idRecu","=",Auth::getIdUser());
            })->orWhere(function($q){
                $q->where("idDemande","=",Auth::getIdUser());
            })->get();
            $membreAttente = array();
            foreach($attente as $temp){
                $membre = m\Membre::where("idUser","=",$temp->idDemande)->first();
                $membreAttente[]=$membre;
            }
            $membreAmis = array();
            foreach($amis as $m){
                if($m->statut != "Attente"){
                    if($m->idDemande == Auth::getIdUser() ){
                        $membre = m\Membre::where("idUser","=",$m->idRecu)->first();
                    }
                    else{
                       $membre = m\Membre::where("idUser","=",$m->idDemande)->first(); 
                    }
                    $membreAmis[] = $membre;
                }
            }
            $liste['demande'] = $membreAttente;
            $liste['amis'] = $membreAmis;
            $v = new v\VueWebSite($liste);
            $v->render('CONTACT');
        }
        else{
            $this->recupererVue('CONTACT');
        }
    }
    
	// fonction qui affiche les demandes d'amis en cours et donne la possibilité de les accepter 
    public function validationContact(){
        #si il veux accepter la demande
        if(isset($_POST['ok'])){
            $a = m\Amis::where("idDemande","=",$_POST['ok'])->where("idRecu","=",Auth::getIdUser());
            $a->delete();
            #impossible d'update avec la clef composite
            $ajout = new m\Amis();
            $ajout->idDemande =$_POST['ok'];
            $ajout->idRecu =Auth::getIdUser();
            $ajout->statut="ok";
            $ajout->save();
            
        }
    }
    
	// fonction qui supprime un contact 
    public function supprimerContact($id){
        $amis = m\Amis::where(function($q) use ($id){
            $q->where("idRecu","=",$id)->where("idDemande","=",Auth::getIdUser());
        })->orWhere(function($q) use ($id){
            $q->where("idDemande","=",$id)->where("idRecu","=",Auth::getIdUser());
        })->delete();
    }

}