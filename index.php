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
    if(isset($_POST['connexion'])){
        if($gest->seConnecter()){
            $app->redirect($_SERVER['SCRIPT_NAME'].'/Accueil');
            printf("Ok");
            printf("Faire acces Accueil");
        }
        else{
            $gest->recupererVue();
        }
    }
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

$app->get('/liste/:token', function($token){
    $cont = new c\ContAffichageListe();
    $cont->afficherListe($token);
});

$app->get('/item/:id', function($id){
    $cont = new c\ContAffichageListe();
    $cont->afficherItemListe($id);
})->name('itemListe');

$app->get('/liste', function(){
    $cont = new c\ContAffichageListe();
    $cont->afficherListes();
});

$app->get('/item/:id/reserver', function($id){
    $cont = new c\ContAffichageListe();
    $cont->afficherReservationItem($id);
});

$app->post('/item/:id/reserver', function($id){
    $cont = new c\ContAffichageListe();
    $cont->reserverItem($id);
})->name('reserverItem');

$app->get('/test/:id', function($id)  {
  $contItem = new c\ContItem();
  $contItem->afficherItem($id);
});


$app->run();