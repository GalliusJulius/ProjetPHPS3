<?php
namespace wishlist\Auth;
use \wishlist\models as m;

session_start();

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
	
	public static function isLogged(){
		return isset($_SESSION['profil']['mail']);
	}
    
    public static function isCreator($token){
        if(self::isLogged()){
            $m = m\Membre::where('email', 'like', $_SESSION['profil']['mail'])->first();
            $res = $m->listes()->where('token', 'like', $token)->first();
            
            if($res != false){
                return true;
            } else{
                return false;
            }
            
        } else{
            return false;
        }
        
        
    }

	public static function deconnexion(){
		$_SESSION=[];
        session_destroy();
	}
}