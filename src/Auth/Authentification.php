<?php
namespace wishlist\Auth;
use \wishlist\models as m;

class Authentification{

    static function createUser($mail,$pass,$passC,$nom,$prenom,$pseudo){
        $count=m\Membre::where('email','=',$mail)->count();
        #$policy = new \PasswordPolicy\Policy;
        #$policy->contains('lowercase', $policy->atLeast(2));
        #$policy->length( 6 ) ;
            if($count == 0 && filter_var($mail,FILTER_VALIDATE_EMAIL)){
                #if($policy->check($pass)){
                    if($pass == $passC){
                        $insert = new m\Membre();
                        $insert->email=$mail;
                        $insert->Nom=$nom;
                        $insert->Prenom=$prenom;
                        $insert->Pseudo=$pseudo;
                        $token = openssl_random_pseudo_bytes(32);
                        $token = bin2hex($token);
                        $insert->comp = $token;
                        $insert->mdp=password_hash($pass . $token,PASSWORD_DEFAULT);
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
        $var=m\Membre::select('mdp','comp')->where('email','=',$user)->first();
        if(!(isset($var) && password_verify($pass . $var->comp,$var->mdp))){
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