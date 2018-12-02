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
            $var=m\Membre::select('mdp')->where('email','=',$_POST['mail'])->first();
            if($var->mdp == ($_POST['pass'])){
                //session_start();
                $_SESSION['mail'] = $_POST['mail'];
                header('Location: Accueil.php');
            }
            else{
                print("<p class=\"erreur\">Mot de passe ou email inconnu</p>");
            }
        }
        else{
            print("<p class=\"erreur\">Problème de connexion</p>");
        }
    }
    
    public function seDeconnecter(){
        if(isset($_POST['deconnexion'])){
            session_start();
            session_destroy();
            header('Location: Index.php');
        }
    }
    
    public function enregistrer(){
        if(isset($_POST['inscription'])){
            $count=m\Membre::where('email','=',$_POST['email'])->count();
            if($count == 0){
                if($_POST['mdp'] == $_POST['mdpc']){
                    $insert = new m\Membre();
                    $insert->email=$_POST['email'];
                    $insert->Nom=$_POST['nom'];
                    $insert->Prénom=$_POST['prenom'];
                    $insert->mdp=$_POST['mdp'];
                    $insert->save();
                }
                else{
                    print("<p class=\"erreur\">Les mots de passes ne sont pas les mêmes</p>");
                }
            }
            else{
                print("<p class=\"erreur\">Mail non disponible</p>");
            }
        }
    }
    
    public function erreurPasCo(){
        header('Location: Index.php');
    }
}
