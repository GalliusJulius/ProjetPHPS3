<?php
namespace wishlist\controleurs;
use \wishlist\models as m;
use \wishlist\vues as v;
use \wishlist\Auth\Authentification as a;

class ControleurConnexion{
    
    public $erreur ="";
    
    public function __construct(){
    }
    
	// fonction qui permet de se deconnecter 
    public function seDeconnecter(){
        a::deconnexion();
    }
    
	// fonction qui permet de rediriger vers la vue de connection après la déconnection 
    public function recupererVue($type){
        $v = new v\VueConnexion($type);
        $v->render($this->erreur);
    }
}
