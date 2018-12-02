<!DOCTYPE html>
<?php
require_once 'vendor/autoload.php';
use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models as m;
use \wishlist\controleurs as c;
$info = parse_ini_file('src/conf/conf.ini');
$db = new DB();
$db->addConnection($info);
$db->setAsGlobal();
$db->bootEloquent();
$gestionConnec = new c\GestionMembre($db);
//si il n'est pas connecté
session_start();
if(!isset($_SESSION['mail'])){
    $gestionConnec->erreurPasCo();
}
//Si il veux se déconnecter
if(isset($_POST['deconnexion'])){
    $gestionConnec->seDeconnecter();
}
?>
<html>
	<head>
        <meta charset="utf-8">
	      <title>Connexion/Inscription</title>
        <link rel='stylesheet'  href='src/css/bootstrap.min.css'/>
	</head>
	<body>
        <div class="container">
            <div class="row">
            <form method="post" action="">
                    <div  class="col col-lg-4"> 
                        <button type="submit" class="btn btn-primary" name="deconnexion">Se déconnecter</button>
                    </div>
                </form>
                <div class="col col-l-4">
                    <a href="Compte.php">
                        <button class="btn btn-primary" >Mon compte</button></a>
                </div>
            </div>
        </div>
    </body> 
 </html>