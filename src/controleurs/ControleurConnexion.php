<?php
namespace wishlist\controleurs;
use \wishlist\models as m;
use \wishlist\vues as v;
use \wishlist\Authentification as a;

class ControleurConnexion{
    
    public $erreur ="";
    
    public function __construct(){
    }
    
    public function seDeconnecter(){
         session_start();
         session_destroy();
    }
    
    public function recupererVue($type){
        $v = new v\VueConnexion($type);
        $v->render($this->erreur);
    }
}
