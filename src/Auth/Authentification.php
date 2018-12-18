<?php
namespace wishlist\Auth;
use \wishlist\models as m;

class Authentification{

    static function createUser($mail,$pass,$passC,$nom,$prenom,$pseudo){
        $count=m\Membre::where('email','=',$mail)->count();
            if($count == 0){
                if($pass == $passC){
                    $insert = new m\Membre();
                    $insert->email=$mail;
                    $insert->Nom=$nom;
                    $insert->Prénom=$prenom;
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
        $var=m\Membre::select('mdp')->where('email','=',$user)->first();
        if(!(isset($var) && password_verify($_POST['pass'],$var->mdp))){
            throw new \Exception("AuthException");
        }
    }
    
    static function loadProfil($mail){
        $_SESSION['profil']['mail'] = $mail;
    }
}