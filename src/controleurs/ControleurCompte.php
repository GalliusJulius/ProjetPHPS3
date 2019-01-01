<?php
namespace wishlist\controleurs;
use \wishlist\vues as v;
use \wishlist\models as m;
use \wishlist\Auth as a;

class ControleurCompte{
    
    protected $erreur = "";
    
    public function recupererVue($type){
        $v = new v\VueAccueil($type,$this->erreur);
        $v->render();
    }
    
    public function miseAjour(){
        session_start();
        $this->erreur="";
        $perso = m\Membre::where("email","=",$_SESSION['profil']['Email'])->first();
        if(isset($_POST['Nom']) && $_POST['Nom'] != ""){
           $perso->Nom= $_POST['Nom'];
        }
        if(isset($_POST['Prenom']) && $_POST['Prenom'] != ""){
           $perso->Prénom= $_POST['Prenom'];
        }
        if(isset($_POST['Pseudo']) && $_POST['Pseudo'] != ""  && filter_var($_POST['Pseudo'],FILTER_SANITIZE_STRING)){
           $perso->Pseudo= $_POST['Pseudo'];
        }
        if(isset($_POST['Email']) && $_POST['Email'] != "" && filter_var($_POST['Email'],FILTER_VALIDATE_EMAIL)){
            if(m\Membre::where("email","=",$_POST['Email'])->count() == 0){
                $perso->email=$_POST['Email'];
                $_SESSION['profil']['Email'] = $_POST['Email'];
            }
            else{
                $this->erreur= "Email déjà liée à un autre compte";
            }
        }
        if(isset($_POST['mdp']) && isset($_POST['mdpc']) && $_POST['mdp'] != "" && $_POST['mdpc'] != ""){
            if($_POST['mdp'] == $_POST['mdpc']){
                $perso->mdp=password_hash($_POST['mdp'],PASSWORD_DEFAULT);
            }
            else{
                $this->erreur= "Les mots de passes ne correspondents pas!";
            }
        }
        $perso->save();
        a\Authentification::loadProfil($_SESSION['profil']['Email']);
    }
}