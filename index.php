<?php
require_once 'vendor/autoload.php';
$app = new \Slim\Slim();
use \wishlist\controleurs as c;
use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\Auth as a;
$db = new DB();
$info= parse_ini_file('src/conf/conf.ini');
$db->addConnection($info);
$db->setAsGlobal();
$db->bootEloquent();

//Routage pour la connexion
$app->get('/',function(){
    $gest = new c\ControleurConnexion();
    $gest->recupererVue("connexion");
});

$app->post('/',function(){
    $app = \Slim\Slim::getInstance();
    $gest = new c\ControleurConnexion();
    //Si on veux se connecter
    if(isset($_POST['connexion'])){
        try{
            a\Authentification::authentificate($_POST['mail'],$_POST['pass']); a\Authentification::loadProfil($_POST['mail']);
            //pas sur que ca soit bon --> PAS BIEN :) --> faire avec les routes nommées
            $app->response->redirect($app->urlFor('Accueil'));
            //$app->redirect($_SERVER['SCRIPT_NAME'].'/Accueil');
        }
         catch(Exception $e){
             $gest->erreur="ER_CONNEXION";
             $gest->recupererVue("connexion");
        }
    }   
});

$app->get('/inscription',function(){
    $gest = new c\ControleurConnexion();
    $gest->recupererVue("inscription");
})->name('Inscription');

$app->post('/inscription',function(){
    $app = \Slim\Slim::getInstance();
    $gest = new c\ControleurConnexion();
    //si on veux s'inscrire
    if(isset($_POST['inscription'])){
        try{
            a\Authentification::createUser($_POST['email'],$_POST['mdp'],$_POST['mdpc'],$_POST['nom'],$_POST['prenom'],$_POST['pseudo']); 
        }
        catch(Exception $e){
            echo($e->getMessage());
            var_dump($e);
            if($e->getMessage()=="mail"){ 
                $gest->erreur="ER_INSCRIPTION2";
            }
            else if($e->getMessage()=="mdp"){
                $gest->erreur="ER_INSCRIPTION1";
            }
        }
        $gest->recupererVue("inscription");
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
         $gest = new c\ControleurConnexion();
         $gest->seDeconnecter();
         $app->redirect($_SERVER['SCRIPT_NAME'].'');
     }
})->name('Accueil');

$app->get('/Compte',function(){
   echo("tkt meme pas"); 
})->name('Compte');
// Revoir route
$app->get('/liste/:token', function($token){
    $cont = new c\ContAffichageListe();
    $cont->afficherListe($token);
})->name('listeCrea');
// Revoir route
$app->get('/liste/:share/partager', function($share){
    $cont = new c\ContAffichageListe();
    $cont->afficherListeInvite($share);
})->name('listeShare');

$app->get('/item/:id', function($id){
    $cont = new c\ContAffichageListe();
    $cont->afficherItemListe($id);
})->name('itemListe');

$app->get('/liste', function(){
    $cont = new c\ContAffichageListe();
    $cont->afficherListes();
})->name('liste');

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
