<?php
require_once 'vendor/autoload.php';

$app = new \Slim\Slim();
use \wishlist\controleurs as c;
use \Illuminate\Database\Capsule\Manager as DB;
$db = new DB();
$info= parse_ini_file('src/conf/conf.ini');
$db->addConnection($info);
$db->setAsGlobal();
$db->bootEloquent();

//Routage pour la connexion (racine)
$app->get('/',function(){
    $gest = new c\GestionMembre();
    if(!isset($_SESSION['mail'])){
        $gest->recupererVue();
    }
    else{
        printf('On va  à l\'accueil');   
    }
});
$app->post('/',function(){
    $app = \Slim\Slim::getInstance();
    $gest = new c\GestionMembre();
    //Si on veux se connecter
    if(isset($_POST['connexion'])){
        //Si on peut se connecter on redirige
        if($gest->seConnecter()){
            $app->redirect($_SERVER['SCRIPT_NAME'].'/Accueil');
        }
        //sinon on redonne la vue de la connexion
        else{
            $gest->recupererVue();
        }
    }
    //si on veux s'inscrire
    else{
        $gest->enregistrer();
        $gest->recupererVue();
    }
});

//Routage dans l'accueil
$app->get('/Accueil',function(){
    $acc = new c\ControleurAccueil();
    $acc->recupererVue();
});

$app->post('/Accueil',function(){
    $app = \Slim\Slim::getInstance();
     if(isset($_POST['deconnexion'])){
         $gest = new c\GestionMembre();
         $gest->seDeconnecter();
         $app->redirect($_SERVER['SCRIPT_NAME'].'');
     }
});

$app->get('/Compte',function(){
   echo("tkt meme pas"); 
})->name('Compte');

$app->get('/liste/:token', function($token){
    $cont = new c\ContAffichageListe();
    $cont->afficherListe($token);
});

$app->get('/item/:id', function($id){
    $cont = new c\ContAffichageListe();
    $cont->afficherItemListe($id);
});

$app->get('/liste', function(){
    $cont = new c\ContAffichageListe();
    $cont->afficherListes();
});

$app->get('/test/:id', function($id)  {
  $contItem = new c\ContItem();
  $contItem->afficherItem($id);
});


$app->get('/item/ajouter/:n/:d', function($n,$d) {
  $contItem = new c\ContItem();
  $contItem->ajouterItem($n,$d);
});


$app->get('/item/supprimer/:id', function($id) {
  $contItem = new c\ContItem();
  $contItem->supprimerItem($id);
});


$app->post('/test/:id/modifier',function($id){
  //if(isset($_POST['ajouter'])){
    $contItem = new c\ContItem();
    //$contItem->ajouterItem($n,$d);
    $contItem->modifier($id);
    //}
}); // voir pour prendre les paramètres


$app->post('/test/:id', function($id)  {
  if(isset($_POST['nom']) && isset($_POST['descr']) && isset($_POST['tarif'])){
    $contItem = new c\ContItem();
    $contItem->modifierItem($id,$_POST);
    $contItem->afficherItem($id);
  }
});



$app->run();