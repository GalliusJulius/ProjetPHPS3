<?php
namespace wishlist\controleurs;
use \wishlist\vues as v;

class ControleurAccueil{
    
    public function recupererVue(){
        $v = new v\VueAccueil();
        $v->render();
    }
}