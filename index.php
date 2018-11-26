<?php

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models as m;

$info = parse_ini_file('src/conf/conf.ini');

$db = new DB();

$db->addConnection( [
 'driver' => $info['driver'],
 'host' => $info['host'],
 'database' => $info['database'],
 'username' => $info['username'],
 'password' => $info['password'],
 'charset' => $info['charset'],
 'collation' => $info['collation'],
 'prefix' => $info['prefix']
] );

$db->setAsGlobal();
$db->bootEloquent();


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
}
**/

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

if(isset($_GET['no'])){
  $liste = m\Liste::where('no','=',$_GET['no'])->first();
  $i = $liste->items()->get();

  foreach ($i as $value) {
    echo("id = ".$value->id.", liste_id = ".$value->liste_id.", nom = ".$value->nom.", descr = ".$value->descr.", img = ".$value->img.", url = ".$value->url.", tarif = ".$value->tarif."<br>");
  }
}
