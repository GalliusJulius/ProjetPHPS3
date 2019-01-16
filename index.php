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

/*
* Routage pour recuperer la vue de la connexion
*/
$app->get('/',function(){
    if(isset($_COOKIE['membre']) and ($_COOKIE['membre'] != NULL)){
        try{
            a\Authentification::loadProfil(unserialize($_COOKIE['membre']));
            $app = \Slim\Slim::getInstance();
            $app->redirect($app->urlFor('accueil'));
        } catch(Exception $e){
             $gest = new c\ControleurConnexion();
             $gest->erreur="ER_CONNEXION";
             $gest->recupererVue("connexion");
        }
    } else{
        $gest = new c\ControleurConnexion();
        $gest->recupererVue("connexion");
    }
})->name('connexion');

/*
* Routage pour la connexion de l'utilisateur
* On connecte l'utilisateur puis on charge son profil
*/
$app->post('/',function(){
    $app = \Slim\Slim::getInstance();
    $gest = new c\ControleurConnexion();
    try{
        a\Authentification::authentificate($_POST['mail'],$_POST['pass']); a\Authentification::loadProfil($_POST['mail']);
        $app->redirect($app->urlFor('accueil'));
    }
     catch(Exception $e){
         $gest->erreur="ER_CONNEXION";
         $gest->recupererVue("connexion");
    }
    
})->name('connexionPost');

/*
* Routage pour recuperer la vue de l'inscription
*/
$app->get('/inscription',function(){
    $gest = new c\ControleurConnexion();
    $gest->recupererVue("inscription");
})->name('Inscription');

/*
* Routage pour l'inscription de l'utilisateur
* On cree l'utilisateur et on l'insere dans la base, on le connecte et on charge son profil
*/
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
       else if($e->getMessage()=="police"){
           $gest->erreur="ER_INSCRIPTION3";
       }
    }
    $gest->recupererVue("inscription");
    }
})->name('insriptionPost');

/*
* Routage pour la recuperation de la vue de l'accueil
*/
$app->get('/Accueil',function(){
    $acc = new c\ControleurCompte();
    $acc->recupererVue("ACCUEIL");
})->name('accueil');

/*
* Routage pour recuperer la vue de l'affichage du compte de l'utilisateur (uniquement si il est connecte)
*/
$app->get('/Compte',function(){
   $acc = new c\ControleurCompte();
    $acc->recupererVue("COMPTE");
})->name('Compte');

/*
* Routage qui permet d'afficher le compte de l'utilisateur demande, ou si c'est l'utilisateur courant, son propre compte
*/
$app->get('/utilisateur/:id',function($id){
    $acc = new c\ControleurCompte();
    $acc->afficherCompte($id);
})->name('user');

/*
* Routage permettant d'ajouter un ami
*/
$app->post('/utilisateur/:id',function($id){
    $app = \Slim\Slim::getInstance();
    if(isset($_POST['add'])&& $_POST['add']=='y'){
        $acc = new c\ControleurCompte();
        $acc->ajouterAmi($id);
        $app->redirect($app->urlFor('user',array('id'=>$id)));
    }
});

/*
* Routage permettant la modification des informations de l'utilisateur courant et sa déconnexion s'il le souhaite
*/
$app->post('/Compte',function(){
    $app = \Slim\Slim::getInstance();
    $acc = new c\ControleurCompte();
    if(isset($_POST['deconnexion'])){
         $gest = new c\ControleurConnexion();
         $gest->seDeconnecter();
         $app->redirect($app->urlFor('connexion'));
     }
    else{
        $acc->miseAjour();
        a\Authentification::loadProfil($_SESSION['profil']['Email']);
       $acc->recupererVue("COMPTE");   
    }
});

/*
* Routage permettant l'affichage de ses contacts
*/
$app->get('/Contact',function(){
   $acc= new c\ControleurCompte();
    $acc->affichageContacts();
})->name('contact');

/*
* Routage permettant la modification de sa liste de contacts
*/
$app->post('/Contact',function(){
    if(isset($_POST['ok'])){
        $acc= new c\ControleurCompte();
        $acc->validationContact();
        $acc->affichageContacts();
    }
    else if(isset($_POST['delUs']) || isset($_POST['del'])){
        $acc= new c\ControleurCompte();
        if(isset($_POST['delUs'])){
            $acc->supprimerContact($_POST['delUs']);
        }
        else{
            $acc->supprimerContact($_POST['del']);   
        }
        $acc->affichageContacts();
    }
});

/*
* Routage permettant d'acceder a la vue de la suppression de son compte
*/
$app->get('/SupprimerCompte',function(){
    $acc = new c\ControleurCompte();
    $acc->recupererVue("SUPPCOMPTE");
})->name('suppCompte');

/*
* Routage permettant d'acceder a la vue de la confirmation de la suppression de son compte
*/ 
$app->post('/SupprimerCompte',function(){
    $acc = new c\ControleurCompte();
    $acc->supprimerCompte();
    $acc->recupererVue("CONFSUPP");
});

/*
* Routage permettant d'afficher toutes les listes qui ont ete creees par l'utilisateur ou qui lui ont ete partagees
*/
$app->get('/MesListes',function(){
    $cont = new c\ContAffichageListe();
    $cont->afficherMesListes("");
})->name('mesListes');

/*
* Routage permettant de supprimer les listes depuis l'endroit ou elles sont affichees et d'ajouter de nouvelles listes
*/
$app->post('/MesListes',function(){
    if(isset($_POST['suppression']) && $_POST['suppression']!=""){
        $cont = new c\ContAffichageListe();
        $cont->supprimerListeShare($_POST['suppression']);
        $cont->afficherMesListes("");
    }
    else{
        $cont = new c\ContAffichageListe();
        $err = $cont->ajouterListe($_POST['url']);
        $cont->afficherMesListes($err);
    }
});

/*
* Routage permettant d'afficher la vue de la creation d'une liste
*/
$app->get('/MesListes/creerListe',function(){
  $cont = new c\ContInstanceListe();
  $cont->creerListe();
})->name('creerListe');

/*
* Routage permettant de creer une nouvelle liste. Une fois la liste creee, l'utilisateur sera considere comme "createur"
*/
$app->post('/MesListes/creerListe',function(){
  $cont = new c\ContInstanceListe();
  $cont->creer_liste();
})->name('creer_liste');

/*
* Routage permettant d'afficher la vue de la modification d'une liste
*/
$app->get('/MesListes/modifierListe/:token',function($token){
  $cont = new c\ContInstanceListe();
  $cont->modifierListe($token);
})->name('modifierListe');

/*
* Routage permettant de modifier une liste
*/
$app->post('/MesListes/modifierListe/:token',function($token){
  $cont = new c\ContInstanceListe();
  $cont->modifier_liste($token);
})->name('modifier_liste');

/*
* Routage permettant de supprimer une liste
*/
$app->get('/MesListes/supprimerListe/:token',function($token){
  $cont = new c\ContInstanceListe();
  $cont->supprimer_liste($token);
})->name('supprimer_liste');

/*
* Routage permettant d'afficher la liste des utilisateurs consideres comme "createurs"
*/
$app->get('/Createurs',function(){
    $cont = new c\ControleurCompte();
    $cont->afficherCreateurs();
})->name('createur');

/*
* Routage permettant d'afficher la vue de la recherche
*/
$app->get('/Recherche',function(){
    $cont = new c\contRecherche();
    $cont->afficherRecherche();
})->name('recherche');

/*
* Routage permettant d'afficher la vue de la recherche avancee
*/
$app->get('/RechercheAvancee',function(){
    $cont = new c\contRecherche();
    $cont->rechercherAvancee();
})->name('rechercheAvancee');

/*
* Routage permettant d'afficher une liste
*/
$app->get('/liste/:token', function($token){
    $cont = new c\ContAffichageListe();
    $cont->afficherListe($token);
})->name('listeCrea');

/*
* Routage qui affiche les listes partagees a quelqu'un
*/
$app->get('/liste/:share/partager', function($share){
    $cont = new c\ContAffichageListe();
    $cont->afficherListeInvite($share);
})->name('listeShare');

/*
* Routage permettant de reserver un item
*/
$app->post('/liste/:share/partager/reserver/:idItem', function($share, $idItem){
    $cont = new c\ContAffichageListe();
    $cont->reserverItem($share, $idItem);
})->name('reserver');

/*$app->get('/item/:id', function($id){
    $cont = new c\ContAffichageListe();
    $cont->afficherItemListe($id);
})->name('itemListe');*/

/*
* Routage permettant de creer une cagnotte
*/
$app->post('/item/:id/cagnotte', function($id){
    $cont = new c\ContCagnotte();
    $cont->creerCagnotte($id);
    
})->name('creerCagnotte');

/*
* Routage permettant de participer a une cagnotte
*/
$app->post('/item/:id/cagnotte/participer', function($id){
    $cont = new c\ContCagnotte();
    try{
        $cont->participerCagnotte($id);
    } catch(\Exception $e){
        $_SESSION['messageErreur'] = "Une erreur est survenue lors de la participation à la cagnotte !";
        $_SESSION['typeErreur'] = "err";
        $app = \Slim\Slim::getInstance();
        $app->redirect($app->urlFor('accueil'));
    }
})->name('participerCagnotte');

/*
* Routage permettant d'afficher toutes les listes publiques
*/
$app->get('/liste_public', function(){
    $cont = new c\ContAffichageListe();
    $cont->afficherListesPublic();
})->name('listePublic');

/*
* Routage permettant d'afficher la vue d'ajout d'un item a une liste
*/
$app->get('/liste/:token/ajouterItem', function($token) {
  $contItem = new c\ContItem();
  $contItem->ajouterItem($token);
})->name('ajouterItem');

/*
* Routage permettant d'ajouter un item a une liste
*/
$app->post('/liste/:token/:ajouter_item', function($token) {
  $contItem = new c\ContItem();
  if(isset($_POST['valider'])){
    $contItem->ajouter_item($token, $_POST['valider']);
  }
})->name('ajouter_item');

/*
* Routage permettant d'afficher la vue de la modification d'un item
*/
$app->get('/liste/:token/modifier/:id',function($token, $id){
  $contItem = new c\ContItem();
  $contItem->modifier($token, $id);
})->name('modifierItem');

/*
* Routage permettant la modification d'un item et la suppression d'une image d'un item
*/
$app->post('/liste/:token/modifier/:id',function($token,$id){
  $contItem = new c\ContItem();
  if(isset($_POST['valider_modif'])){
    $contItem->modifierItem($token, $id);
  } elseif(isset($_POST['supprimer_img'])) {
      $contItem->supprimer_image($token, $id);
  }
})->name('modifier_item');

/*
* Routage permettant la suppression d'un item
*/
$app->get('/liste/:token/:id/supprimer', function($token, $id) {
  $contItem = new c\ContItem();
  $contItem->supprimerItem($token, $id);
})->name('supprimer');

/*
* Routage permettant d'afficher un message a une liste par le createur de cette liste
*/
$app->post('/liste/:token', function($token) {
    $cont = new c\ContAffichageListe();
    $cont->ajouterMessageListe($token);
})->name('ajoutMsgListe');

/*
* Routage permettant la redirection vers la liste des createurs si l'utilisateur est un createur. Sinon, redirige vers les listes partagees.
* Permet egalement de ne pas afficher les erreurs lors de la redirection
*/
$app->get('/liste/demandeAcces/:token', function($token) {
    $cont = new c\ContAffichageListe();
    $cont->demandeAcces($token);
})->name('demandeAcces');


$app->run();
