<?php
namespace wishlist\controleurs;
use \wishlist\models as m;
use \wishlist\vues as v;

class GestionMembre{
    
    private $erreurC ="";
    private $erreurI ="";
    
    public function __construct(){
    }
    
    public function seConnecter(){
        $erreurI ="";
        $res = false;
        $var=m\Membre::select('mdp')->where('email','=',$_POST['mail'])->first();
        if(isset($var) && password_verify($_POST['pass'],$var->mdp)){
            $_SESSION['mail'] = $_POST['mail'];
            $res=true;
        }
        else{
            $this->erreurC = ("<p class=\"erreur\">Mot de passe ou email inconnu</p>");
        }
        return $res;
    }
    
    public function seDeconnecter(){
         session_start();
         session_destroy();
    }
    
    public function recupererVue(){
        $v = new v\VueConnexion();
        $v->render($this->erreurI,$this->erreurC );
    }
    
    public function enregistrer(){
        $erreurC ="";
        if(isset($_POST['inscription'])){
            $count=m\Membre::where('email','=',$_POST['email'])->count();
            if($count == 0){
                if($_POST['mdp'] == $_POST['mdpc']){
                    $insert = new m\Membre();
                    $insert->email=$_POST['email'];
                    $insert->Nom=$_POST['nom'];
                    $insert->Prénom=$_POST['prenom'];
                    $insert->mdp=password_hash($_POST['mdp'],PASSWORD_DEFAULT);
                    $insert->save();
                }
                else{
                     $this->erreurI = ("<p class=\"erreur\">Les mots de passes ne sont pas les mêmes</p>");
                }
            }
            else{
                 $this->erreurI =("<p class=\"erreur\">Mail non disponible</p>");
            }
        }
    }
}
