<!DOCTYPE html>
<?php
require_once 'vendor/autoload.php';
use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models as m;
use \wishlist\controleurs as c;
<<<<<<< HEAD
=======

>>>>>>> f1fd81e1144e20dd0a299846be4d14038e798cdd
$info = parse_ini_file('src/conf/conf.ini');
$db = new DB();
$db->addConnection($info);
$db->setAsGlobal();
$db->bootEloquent();
<<<<<<< HEAD
$gestionConnec = new c\GestionMembre($db);
if(isset($_POST['connexion'])){
    $gestionConnec->seConnecter()   ;
=======

$contItem = new c\ContItem($db);
if(isset($_GET['nom']) && isset($_GET['desc'])){
  $contItem->ajouterItem($_GET['nom'],$_GET['desc']);
  echo 'fait';
}else{
  echo 'pas fait';
}


/**
$items = m\Item::select('id', 'liste_id', 'nom', 'descr', 'img', 'url', 'tarif')->get();
foreach ($items as $value) {
  echo("id = ".$value->id.", liste_id = ".$value->liste_id.", nom = ".$value->nom.", descr = ".$value->descr.", img = ".$value->img.", url = ".$value->url.", tarif = ".$value->tarif."<br>");
}
*/
/**
if(isset($_GET['id'])){
  $item = m\Item::where('id','=',$_GET['id'])->first();
}
echo("id = ".$item->id.", liste_id = ".$item->liste_id.", nom = ".$item->nom.", descr = ".$item->descr.", img = ".$item->img.", url = ".$item->url.", tarif = ".$item->tarif."<br>");
*/

/** insertion
$i = new m\Item();
$i->liste_id = 0;
$i->nom = 'testInsert';

$i->save();
echo 'fait';
 ?>
 */

/**
$l = m\Liste::where('no','=','1')->first();
$i = $l->items()->get();

foreach ($i as $value) {
  echo("id = ".$value->id.", liste_id = ".$value->liste_id.", nom = ".$value->nom.", descr = ".$value->descr.", img = ".$value->img.", url = ".$value->url.", tarif = ".$value->tarif."<br>");
>>>>>>> f1fd81e1144e20dd0a299846be4d14038e798cdd
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

<<<<<<< HEAD
=======
/**
$item = m\Item::where('id','=',1)->first();
$liste = $item->liste()->first();

echo("no = ".$liste->no.", user_id = ".$liste->user_id.", titre = ".$liste->titre.", description = ".$liste->description.", expiration = ".$liste->expiration.", token = ".$liste->token."<br>");
*/

// indiquer le nom de la liste de souhait dans la liste des items
/**
$items = m\Item::get();
foreach ($items as $value) {
  $liste = $value->liste()->first();
  if(isset($liste)){
      echo 'id : '.$value->id.', nom : '.$value->nom.', nom de la liste : '.$liste->titre.'<br>';
  }else{
      echo 'id : '.$value->id.', nom : '.$value->non.'<br>';
  }
}
*/


//lister les items d'une liste donnée dont l'id est passé en paramètre

/**
if(isset($_GET['no'])){
  $liste = m\Liste::where('no','=',$_GET['no'])->first();
  $i = $liste->items()->get();

  foreach ($i as $value) {
    echo("id = ".$value->id.", liste_id = ".$value->liste_id.", nom = ".$value->nom.", descr = ".$value->descr.", img = ".$value->img.", url = ".$value->url.", tarif = ".$value->tarif."<br>");
  }
}
*/
>>>>>>> f1fd81e1144e20dd0a299846be4d14038e798cdd
