<?php
namespace wishlist\Auth;
use \wishlist\models as m;

class Authentification{

    static function createUser($mail,$pass,$passC,$nom,$prenom,$pseudo){
        $count=m\Membre::where('email','=',$mail)->count();
            if($count == 0 && filter_var($mail,FILTER_VALIDATE_EMAIL)){
                if($pass == $passC){
                    $insert = new m\Membre();
                    $insert->email=$mail;
                    $insert->Nom=$nom;
                    $insert->Prénom=$prenom;
                    $insert->Pseudo=$pseudo;
                    $insert->mdp=password_hash($pass,PASSWORD_DEFAULT);
                    $insert->save();
                }
                else{
                    throw new \Exception("mdp");
                }
            }
        else{
            throw new \Exception("mail");
        }
    }

    static function authentificate($user,$pass){
        session_start();
        if(!filter_var($user,FILTER_VALIDATE_EMAIL)){
            throw new \Exception("mailInvalide");
        }
        $var=m\Membre::select('mdp')->where('email','=',$user)->first();
        if(!(isset($var) && password_verify($_POST['pass'],$var->mdp))){
            throw new \Exception("AuthException");
        }
    }
    
    static function loadProfil($mail){
        //session_start();
        $profil = m\Membre::where('email',"=",$mail)->first();
        $_SESSION['connect'] = "oui";
        $_SESSION['profil']['Email']=$mail;
        $_SESSION['profil']['Nom']=$profil->Nom;
        $_SESSION['profil']['Prenom']=$profil->Prénom;
        $_SESSION['profil']['Pseudo']=$profil->Pseudo;
    }
}