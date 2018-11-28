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
if(isset($_POST['connexion'])){
    $gestionConnec->seConnecter()   ;
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
            <div class="row justify-content-md-center">
                <div class="col col-lg-4">
                    <form method="post" action="">
                        <p>
                            <input type="text" name="nom" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Prénom" required/>
                        </p>
                        <p>
                            <input type="text" name="nom" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Nom" required/>
                        </p>
                        <p>
                            <input type="email" name="nom" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Adresse mail" required/>
                        </p>
                        <p>
                           <input type="text" name="nom" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Pseudo" required/>
                        </p>
                        <p>
                            <input type="password" name="nom" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Mot de passe" required/>
                        </p>
                        <p>
                            <input type="password" name="nom" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Confirmez mot de passe" required/>
                        </p>
                        <p>
                            <button type="submit" class="btn btn-primary" name="inscription">Inscription</button>
                        </p>
                    </form>
                </div>
                <div class = "col-lg-3">
                    <form method="post" action="Accueil.php">
                     <p>
                            <input type="email" name="mail" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Votre adresse mail" required/>
                        </p>
                        <p>
                           <input type="password" name="pass" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Mot de passe" required/>
                        </p>
                        <p>
                            <button type="submit" class="btn btn-primary" name="connexion" value="connec">Connexion</button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </body> 
 </html>

