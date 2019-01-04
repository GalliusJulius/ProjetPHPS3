<?php
require_once 'vendor/autoload.php';
#require_once 'passwordPolicy/lib/password.php';
$app = new \Slim\Slim();
use \wishlist\controleurs as c;
use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\Auth as a;
$db = new DB();
$info= parse_ini_file('src/conf/conf.ini');
$db->addConnection($info);
$db->setAsGlobal();
$db->bootEloquent();
session_start();
//Routage pour la connexion
$app->get('/',function(){
    $gest = new c\ControleurConnexion();
    $gest->recupererVue("connexion");
})->name('connexion');

$app->post('/',function(){
    $app = \Slim\Slim::getInstance();
    $gest = new c\ControleurConnexion();
    //Si on veux se connecter
    //if(isset($_POST['connexion'])){
    try{    
        a\Authentification::authentificate($_POST['mail'],$_POST['pass']); a\Authentification::loadProfil($_POST['mail']);
        $app->redirect($_SERVER['SCRIPT_NAME'].'/Accueil');
    }
     catch(Exception $e){
         $gest->erreur="ER_CONNEXION";
         $gest->recupererVue("connexion");
    }
    //}   
})->name('connexionPost');

//Routage pour l'inscription
$app->get('/inscription',function(){
    $gest = new c\ControleurConnexion();
    $gest->recupererVue("inscription");
})->name('Inscription');

$app->post('/inscription',function(){
    $app = \Slim\Slim::getInstance();
    $gest = new c\ControleurConnexion();
    if(isset($_POST['inscription'])){
    try{
        a\Authentification::createUser($_POST['email'], $_POST['mdp'], $_POST['mdpc'], $_POST['nom'], $_POST['prenom'], $_POST['pseudo']);
        
        a\Authentification::authentificate($_POST['email'],$_POST['mdp']);
        
        a\Authentification::loadProfil($_POST['email']);
        
        $app->redirect($app->urlFor('accueil'));
    }
   catch(Exception $e){
       if($e->getMessage()=="mail"){ 
          $gest->erreur="ER_INSCRIPTION2";
        }
        else if($e->getMessage()=="mdp"){
            $gest->erreur="ER_INSCRIPTION1";
        }
    }
    $gest->recupererVue("inscription");
    }
})->name('insriptionPost');

//Routage dans l'accueil
$app->get('/Accueil',function(){
    $acc = new c\ControleurCompte();
    $acc->recupererVue("accueil");
})->name('accueil');

$app->post('/Accueil',function(){
    $app = \Slim\Slim::getInstance();
     if(isset($_POST['deconnexion'])){
         $gest = new c\ControleurConnexion();
         $gest->seDeconnecter();
         $app->redirect($app->urlFor('connexion'));
     }
})->name('Accueil');

//Routage pour la gestion de compte
$app->get('/Compte',function(){
   $acc = new c\ControleurCompte();
    $acc->recupererVue("compte");
})->name('Compte');

$app->post('/Compte',function(){
   $acc = new c\ControleurCompte();
   $acc->miseAjour();
    a\Authentification::loadProfil($_SESSION['profil']['Email']);
   $acc->recupererVue("compte");
});

$app->get('/SupprimerCompte',function(){
    $acc = new c\ControleurCompte();
    $acc->recupererVue("suppCompte");
})->name('suppCompte');

$app->post('/SupprimerCompte',function(){
    $acc = new c\ControleurCompte();
    $acc->supprimerCompte();
    $acc->recupererVue("confSupp");
});

$app->get('/MesListes',function(){
    $cont = new c\ContAffichageListe();
    $cont->afficherMesListes("");
})->name('mesListes');

$app->post('/MesListes',function(){
    $cont = new c\ContAffichageListe();
    $err = $cont->ajouterListe($_POST['token']);
    $cont->afficherMesListes($err);
});

$app->get('/liste/:token', function($token){
    $cont = new c\ContAffichageListe();
    $cont->afficherListe($token);
})->name('listeCrea');

// Revoir route
$app->get('/liste/:share/partager', function($share){
    $cont = new c\ContAffichageListe();
    $cont->afficherListeInvite($share);
})->name('listeShare');

$app->post('/liste/:share/partager/reserver/:idItem', function($share, $idItem){
    $cont = new c\ContAffichageListe();
    $cont->reserverItem($share, $idItem);
})->name('reserver');

$app->get('/item/:id', function($id){
    $cont = new c\ContAffichageListe();
    $cont->afficherItemListe($id);
})->name('itemListe');

$app->get('/liste', function(){
    $cont = new c\ContAffichageListe();
    $cont->afficherListesUtilisateurs();
})->name('liste');

$app->get('/liste_public', function(){
    $cont = new c\ContAffichageListe();
    $cont->afficherListesPublic();
})->name('listePublic');

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
});     

$app->post('/test/:id', function($id)  {
  if(isset($_POST['nom']) && isset($_POST['descr']) && isset($_POST['tarif'])){
    $contItem = new c\ContItem();
    $contItem->modifierItem($id,$_POST);
    $contItem->afficherItem($id);
  }
});


$app->run();
