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
$courant = m\Membre::select('nom','prénom')->where('email','=',$_SESSION['mail'])->first();
?>
<html>
	<head>
        <meta charset="utf-8">
	      <title>Connexion/Inscription</title>
        <link rel='stylesheet'  href='src/css/bootstrap.min.css'/>
	</head>
	<body>
        <div class="container">
            <h1>Modification du compte</h1>
            <ul>
            <li>Nom : <?php printf($courant->nom) ?></li>
            <li>Prenom : <?php printf($courant->prénom)?></li>
            <li>Email : <?php printf($_SESSION['mail']) ?></li>
            </ul>
        </div>
    </body> 
 </html>