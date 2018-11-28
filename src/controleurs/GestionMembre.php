<?php
namespace wishlist\controleurs;
require_once 'vendor/autoload.php';
use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models as m;

class GestionMembre{
    
    private $db;
    public function __construct($connec){
        $this->db = $connec;
    }
    
    public function seConnecter(){
        if(isset($_POST['connexion'])){
            $var=m\Membre::select('mdp')->  where('email','=',$_POST['mail'])->first();
            if($var->mdp == ($_POST['pass'])){
                header('Location: Accueil.php');
            }
        }
        else{
            print("Problème de connexion");
        }
    }
    
    public function seDeconnecter(){
        
    }
    
    public function enregistrer(){
        
    }
}
