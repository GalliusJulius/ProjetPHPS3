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
        $app->redirect($app->urlFor('Accueil'));
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

$app->get('/utilisateur/:id',function($id){
    $acc = new c\ControleurCompte();
    $acc->afficherCompte($id);
})->name('user');

$app->post('/utilisateur/:id',function($id){
    $app = \Slim\Slim::getInstance();
    if(isset($_POST['add'])&& $_POST['add']=='y'){
        $acc = new c\ControleurCompte();
        $acc->ajouterAmi($id);
        $app->redirect($app->urlFor('user',array('id'=>$id)));
    }
});

$app->post('/Compte',function(){
   $acc = new c\ControleurCompte();
   $acc->miseAjour();
    a\Authentification::loadProfil($_SESSION['profil']['Email']);
   $acc->recupererVue("compte");
});

$app->get('/Contact',function(){
   $acc= new c\ControleurCompte();
    $acc->affichageContacts();
})->name('contact');

//routage dans la confirmation de la suppression
$app->get('/SupprimerCompte',function(){
    $acc = new c\ControleurCompte();
    $acc->recupererVue("suppCompte");
})->name('suppCompte');

$app->post('/SupprimerCompte',function(){
    $acc = new c\ControleurCompte();
    $acc->supprimerCompte();
    $acc->recupererVue("confSupp");
});

//routage dans le gestionnaire de listes
$app->get('/MesListes',function(){
    $cont = new c\ContAffichageListe();
    $cont->afficherMesListes("");
})->name('mesListes');

$app->post('/MesListes',function(){
    if(isset($_POST['suppression']) && $_POST['suppression']!=""){
        $cont = new c\ContAffichageListe();
        $cont->supprimerListeShare($_POST['suppression']);
        $cont->afficherMesListes("");
    }
    else{
        $cont = new c\ContAffichageListe();
        $err = $cont->ajouterListe($_POST['token']);
        $cont->afficherMesListes($err);
    }
});

$app->get('/MesListes/creerListe',function(){
  $cont = new c\ContInstanceListe();
  $cont->creerListe();
})->name('creerListe');

$app->post('/MesListes/creerListe',function(){
  $cont = new c\ContInstanceListe();
  $cont->creer_liste();
})->name('creer_liste');

$app->get('/MesListes/modifierListe/:token',function($token){
  $cont = new c\ContInstanceListe();
  $cont->modifierListe($token);
})->name('modifierListe');

$app->post('/MesListes/modifierListe/:token',function($token){
  $cont = new c\ContInstanceListe();
  $cont->modifier_liste($token);
})->name('modifier_liste');

$app->get('/MesListes/supprimerListe/:token',function($token){
  $cont = new c\ContInstanceListe();
  $cont->supprimer_liste($token);
})->name('supprimer_liste');

$app->get('/Createurs',function(){
    $cont = new c\ControleurCompte();
    $cont->afficherCreateurs();
})->name('createur');

$app->get('/Recherche',function(){
    $cont = new c\contRecherche();
    $cont->afficherRecherche();
})->name('recherche');

$app->get('/RechercheAvancee',function(){
    $cont = new c\contRecherche();
    $cont->rechercherAvancee();
})->name('rechercheAvancee');

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

/*$app->get('/item/:id', function($id){
    $cont = new c\ContAffichageListe();
    $cont->afficherItemListe($id);
})->name('itemListe');*/

$app->post('/item/:id/cagnotte', function($id){
    $cont = new c\ContCagnotte();
    $cont->creerCagnotte($id);
})->name('creerCagnotte');

$app->post('/item/:id/cagnotte/participer', function($id){
    $cont = new c\ContCagnotte();
    $cont->participerCagnotte($id);
})->name('participerCagnotte');

$app->get('/liste_public', function(){
    $cont = new c\ContAffichageListe();
    $cont->afficherListesPublic();
})->name('listePublic');

$app->get('/liste/:token/ajouterItem', function($token) {
  $contItem = new c\ContItem();
  $contItem->ajouterItem($token);
})->name('ajouterItem');

$app->post('/liste/:token/:ajouter_item', function($token) {
  $contItem = new c\ContItem();
  if(isset($_POST['valider'])){
    $contItem->ajouter_item($token, $_POST['valider']);
  }
})->name('ajouter_item');

$app->get('/liste/:token/modifier/:id',function($token, $id){
  $contItem = new c\ContItem();
  $contItem->modifier($token, $id);
})->name('modifierItem');

$app->post('/liste/:token/modifier/:id',function($token,$id){
  $contItem = new c\ContItem();
  if(isset($_POST['valider_modif'])){
    $contItem->modifierItem($token, $id);
  } elseif(isset($_POST['supprimer_img'])) {
      $contItem->supprimer_image($token, $id);
  }
})->name('modifier_item');

$app->get('/liste/:token/:id/supprimer', function($token, $id) {
  $contItem = new c\ContItem();
  $contItem->supprimerItem($token, $id);
})->name('supprimer');

$app->post('/liste/:token', function($token) {
    $cont = new c\ContAffichageListe();
    $cont->afficherMessageListe($token);
})->name('ajoutMsgListe');


$app->run();
