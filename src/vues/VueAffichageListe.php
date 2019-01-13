<?php

namespace wishlist\vues;

use \wishlist\Auth\Authentification as Auth;
use \wishlist\models\Membre;

const LISTES = 1.0;
const LISTES_CREA = 1.1;
const LISTE_CREA = 2.0;
const LISTE_CO = 2.1;
const LISTE_INV = 2.2;
const ITEM = 3.0;
const RESERVER = 4.0;


class VueAffichageListe {

    private $liste, $item, $app;

    public function __construct($tab) {
        if(isset($tab['liste'])){
            $this->liste = $tab['liste'];
        }

        if(isset($tab['item'])){
            $this->item = $tab['item'];
        }

        $this->app = \Slim\Slim::getInstance();
    }

    private function affichageListesCrea() {
        $path = './';

        $html = '<section><ul class="listes">';

        foreach($this->liste as $l){

            if(isset($l)){
                $html .= '<li><p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><p class="date">' . $l->expiration . '</p>';
                $html .= '<form method="GET" action="' . $this->app->urlFor('listeCrea', array('token' => $l->token)) . '">';
                $html .= '<button class="btn btn-primary">Détails</button>';
                $html .= '<p>Nombre de réservations : ' . count($l->reservation()->get()) . '</p>';
                $html .= "</form>";
                $html .= '</li>';
            }
        }

        $html .= '<form method="GET" action="#">'; // TODO mettre route **TRISTAN**
        $html .= '<button class="btn btn-primary">Ajouter une liste</button>';
        $html .= '</form>';

        $html .= '</ul></section>';

        return array('html' => $html, 'path' => $path);
    }

    private function affichageListes() {
        $path = './';

        $html = '<section><ul class="listes">';

        foreach($this->liste as $l){

            if(isset($l)){
                $html .= '<li><p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><p class="date">' . $l->expiration . '</p>';
                $html .= '<form method="GET" action="' . $this->app->urlFor('listeCrea', array('token' => $l->token)) . '">';
                $html .= '<button class="btn btn-primary">Détails</button>';
                $html .= '<p>Nombre de réservations : ' . count($l->reservation()->get()) . '</p>';
                $html .= "</form>";
                $html .= '</li>';
            }
        }

        $html .= '</ul></section>';

        return array('html' => $html, 'path' => $path);
    }

    private function affichageListeCrea() {
        $path = '../';

        $html = '<section class="listes">';
        $cpt = 1;


        $l = $this->liste;

        if(isset($l)){
            $items = $l->items()->get();

            $html .= '<p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><div class="row items">';

            foreach($items as $i){
                $reserv = $i->reservation()->first();

                if(isset($reserv) and ($i->cagnotte == 0)){
                    $html .= '<div class="reserve col col-l-3">';
                } else{
                    $html .= '<div class="col col-l-3">';
                }
                $html .= '<p class="nom"><h4>' . $i->nom . '</h4></p><img src="../src/img/' . $i->img . '"><p class="tarif">' . $i->tarif .  ' €</p>' . '<br/><br/>';


                if(isset($reserv)){

                    $html .= '<p>Cet item a été réservé !</p>';
                    $html .= '<button class="details btn btn-primary h' . $cpt . '">Détails</button>';
                    $html .= '<button class="message btn btn-primary h' . $cpt . '">Voir le message</button>';

                } elseif($i->cagnotte == 0){
                    $html .= '<button class="details btn btn-primary h' . $cpt . '">Détails</button>';
                    $html .= '<form method="GET" action= "' . $this->app->urlFor('modifierItem', array('id' => $i->id,'token' => $l->token)) . '">';
                    $html .= '<button class="btn btn-primary">Modifier</button>';
                    $html .= '</form>';

                    $html .= '<form method="GET" action= "' . $this->app->urlFor('supprimer', array('id' => $i->id,'token' => $l->token)) . '">';
                    $html .= '<button class="btn btn-primary">Supprimer</button>';
                    $html .= '</form>';
                    
                    $html .= '<form method="POST" action= "' . $this->app->urlFor('creerCagnotte', array('id' => $i->id)) . '">';
                    $html .= '<button class="btn btn-primary">Créer une cagnotte</button>';
                    $html .= '</form>';
                } else{
                    $html .= '<button class="details btn btn-primary h' . $cpt . '">Détails</button>';
                    $html .= '<form method="GET" action= "' . $this->app->urlFor('modifierItem', array('id' => $i->id,'token' => $l->token)) . '">';
                    $html .= '<button class="btn btn-primary">Modifier</button>';
                    $html .= '</form>';

                    $html .= '<form method="GET" action= "' . $this->app->urlFor('supprimer', array('id' => $i->id,'token' => $l->token)) . '">';
                    $html .= '<button class="btn btn-primary">Supprimer</button>';
                    $html .= '</form>';
                }


                $html .= '<section class="details hidden hide' . $cpt . '"><h6 class="hidden">Description :</h6>';
                $html .= '<p class="hidden desc">' . $i->descr . '</p>';

                if($i->url != null or $i->url != ""){
                    $html .= '<a class="hidden" target="_blank" href="' . $i->url . '">Produit disponible ici !</a>';
                } else{
                    $html .= '<p class="hidden">Aucune URL associé !</p>';
                }

                $html .= '</section>';


                if(isset($reserv)){
                    $html .= '<section class="message hidden hide' . $cpt . '">';

                    $html .= '<h6>Messages :</h6>';
                    $html .= '<p class="message">' . $reserv->message . '</p>';

                }

                $html .= '</div>';


                $cpt++;
            }

            $html .= '</div>';
            $html .= '<p class="date">Date d\'échéance :</p><p class="date">' . $l->expiration . '</p>';

        }

        $html .= '<form method="GET"  action= "' . $this->app->urlFor('ajouterItem', array('token' => $l->token)) . '">';
        $html .= '<button class="btn btn-primary">Ajouter un item</button>';
        $html .= '</form>';

        $html .= '<button class="partager btn btn-primary">Partager</button>';
        if(isset($l)){
            $html .= '<div class="partager hidden hide modal">';
            $html .= '<div class="form">';
            $html .= '<h6>Lien de partage :</h6>';
            $html .= '<p>Le lien de partage vous permet de partager votre liste à qui vous souhaitez, même des personnes qui ne sont pas inscrites sur le site.</p>';
            $html .= '<input type="text" name="lien" value="' . $_SERVER['HTTP_HOST'] . $this->app->urlFor('listeShare', array('share' => $l->share)) . '" disabled>';
            $html .= '<button class="fermer btn btn-primary">Fermer</button>';
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</section>';

        return array('html' => $html, 'path' => $path);
    }

    private function affichageListeInvite($n) {
        $path = '../../';

        $html = '<section class="listes">';
        $cpt = 1;

        $l = $this->liste;

        if(isset($l)){
            $items = $l->items()->get();

            $html .= '<p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><div class="row items">';

            foreach($items as $i){

                
                
                if($i->cagnotte == 1){
                    
                    $html .= '<div class="col col-l-3">';

                    $html .= '<p class="nom"><h4>' . $i->nom;

                    $html .= '</h4></p><img src="' . $path . 'src/img/';

                    $html .= $i->img . '"><p class="tarif">' . $i->tarif .  ' €</p>' . '<br/><br/>';

                    $html .= '<button class="details btn btn-primary h' . $cpt . '">Détails</button>';


                    
                    
                    $html .= '<button class="btn btn-primary cagnotte h' . $cpt . '">Participer à la cagnotte</button>';

                    $html .= '<div class="cagnotte modal h' . $cpt . '"><div class="form">';
                    $html .= '<form id="Cagn" method="POST" action="' . $this->app->urlFor('participerCagnotte', array('id' => $i->id)) . '">';
                    $html .= '<p>Nom de l\'item auquel vous participer : </p><input type="text" name="nomItem" value="' . $i->nom . '" disabled>';
                    $html .= '<p>Montant de participation : </p><input type="text" name="montant" value="" required>';

                    $n = ''; $p = '';
                    $idUser = Auth::getIdUser();

                    if(isset($idUser)){
                        $m = Membre::where('idUser', '=', $idUser)->first();
                        $n = $m->Nom;
                        $p = $m->Prénom;
                    }

                    
                    $html .= '<p>Votre nom : </p><input type="text" name="nom" value="' . $n . '" required>';
                    $html .= '<p>Votre prénom : </p><input type="text" name="prénom"
                    value="' . $p . '" required>';
                    $html .= '<p>Message : </p><textarea rows="5" cols="50" type="text" name="message" value="" form="Cagn"></textarea>';

                    $html .= '<button type="submit" class="btn btn-primary confirmerC h' . $cpt . '">Participer</button>';

                    $html .= '</form>';
                    $html .= '<button class="btn btn-primary annulerC h' . $cpt . '">Annuler</button>';

                    $html .= '</div>';
                    $html .= '</div>';
                    
                    
                    $html .= '<section class="details hidden hide' . $cpt . '"><h6 class="hidden">Description :</h6>';
                    $html .= '<p class="hidden desc">' . $i->descr . '</p>';

                    if($i->url != null and $i->url != ""){
                        $html .= '<a class="hidden" target="_blank" href="' . $i->url . '">Produit disponible ici !</a>';
                    } else{
                        $html .= '<p class="hidden">Aucune URL associé !</p>';
                    }
                    $html .= '</section>';


                    $html .= '</div>';
                    
                } else{
                    $reserv = $i->reservation()->first();
                    
                    if(isset($reserv)){
                        $html .= '<div class="reserve col col-l-3">';
                    } else{
                        $html .= '<div class="col col-l-3">';
                    }

                    $html .= '<p class="nom"><h4>' . $i->nom;

                    $html .= '</h4></p><img src="' . $path . 'src/img/';

                    $html .= $i->img . '"><p class="tarif">' . $i->tarif .  ' €</p>' . '<br/><br/>';

                    $html .= '<button class="details btn btn-primary h' . $cpt . '">Détails</button>';


                    if(isset($reserv)){

                        $html .= '<button disabled class="btn btn-primary reserver h' . $cpt . '">Réserver</button>';

                        $html .= '<div class="reserv">';
                        $html .= '<p>Cet item a déjà été réservé par :</p>';
                        $html .= '<p>' . $reserv->prénom . ' ' . $reserv->nom . '</p>';
                        $html .= '</div>';

                    } else{

                        $html .= '<button class="btn btn-primary reserver h' . $cpt . '">Réserver</button>';

                        $html .= '<div class="reserver modal h' . $cpt . '"><div class="form">';
                        $html .= '<form id="Reserv" method="POST" action="' . $this->app->urlFor('reserver', array('share' => $l->share, 'idItem' => $i->id)) . '">';
                        $html .= '<p>Nom de l\'item à reserver : </p><input type="text" name="nomItem" value="' . $i->nom . '" disabled>';
                        $html .= '<p>Prix de réservation : </p><input type="text" name="prix" value="' . $i->tarif . '" disabled required>';

                        $n = ''; $p = '';
                        $idUser = Auth::getIdUser();

                        if(isset($idUser)){
                            $m = Membre::where('idUser', '=', $idUser)->first();
                            $n = $m->Nom;
                            $p = $m->Prénom;
                        }

                        $html .= '<p>Votre nom : </p><input type="text" name="nom" value="' . $n . '" required>';
                        $html .= '<p>Votre prénom : </p><input type="text" name="prénom"
                        value="' . $p . '" required>';
                        $html .= '<p>Message : </p><textarea rows="5" cols="50" type="text" name="message" value="" form="Reserv"></textarea>';

                        $html .= '<button type="submit" class="btn btn-primary confirmerR h' . $cpt . '">Réserver</button>';

                        $html .= '</form>';
                        $html .= '<button class="btn btn-primary annulerR h' . $cpt . '">Annuler</button>';

                        $html .= '</div>';
                        $html .= '</div>';

                    }

                    $html .= '<section class="details hidden hide' . $cpt . '"><h6 class="hidden">Description :</h6>';
                    $html .= '<p class="hidden desc">' . $i->descr . '</p>';

                    if($i->url != null and $i->url != ""){
                        $html .= '<a class="hidden" target="_blank" href="' . $i->url . '">Produit disponible ici !</a>';
                    } else{
                        $html .= '<p class="hidden">Aucune URL associé !</p>';
                    }
                    $html .= '</section>';


                    $html .= '</div>';
                }
                
                $cpt++;
                
            }

            $html .= '</div>';
            $html .= '<p class="date">Date d\'échéance :</p><p class="date">' . $l->expiration . '</p>';
        }

        $html .= '</section>';

        return array('html' => $html, 'path' => $path);
    }

    private function ajouterItem() {
      $path = '../../';
      $html = '<section>';
      $ajouter_item = $this->app->urlFor('ajouter_item');

  //    $html .= '<li><p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><p class="date">' . $l->expiration . '</p>';
    //  $html .= '<form method="GET" action="' . $this->app->urlFor('ajouterItem', array('token' => $l->token)) . '">';
    //  $html .= '<button class="btn btn-primary">Détails</button>';
    //  $html .= "</form>";
      $html .= '<div class="row"><div class="col-md-2">';
      $html .= '<form method="post" action="$ajouter_item" enctype="multipart/form-data">';
      $html .= '<p><input type="text" name="nom" class="form-control" aria-describedby="emailHelp" placeholder="Nom" required autofocus/></p>';
      $html .= '<p><input type="text" name="description" class="form-control" aria-describedby="emailHelp" placeholder="Description" required/></p>';
      $html .= '<p><input type="number" name="tarif" class="form-control" aria-describedby="emailHelp" placeholder="Tarif" required/></p>';
      $html .= '<p><input type="text" name="url" class="form-control" aria-describedby="emailHelp" placeholder="lien utile"/></p>';
      $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />';
      $html .= '<p><input type="file" name="image" id="image" accept=".png, .jpg, .jpeg" /></p>';
      $html .= '<p><button type="submit" class="btn btn-primary" name="valider" value="ajouter_item">Valider</button></p>';
      $html .= '</form>';
      $html .= '</div></div>';

      $html .= '</section>';

      return array('html' => $html, 'path' => $path);
    }

    private function modifierItem() {
      $path = '../../../';
      $html = '<section>';
      $i = $this->item;
      $modifier_item = $this->app->urlFor('modifier_item');
      $id=$i->id;

      $html .= '<div class="row"><div class="col-md-2">';
      $html .= '<form method="POST" action="'.$id.'/$modifier_item" enctype="multipart/form-data">';
      $html .= '<p><input type="text" name="nom" class="form-control" aria-describedby="emailHelp" placeholder="Nom" value="'.$i->nom.'" autofocus/></p>';
      $html .= '<p><textarea rows="5" cols="50" type="text" name="description" value="">'.$i->descr.'</textarea></p>:';
      //$html .= '<p><input type="text" name="description" class="form-control" aria-describedby="emailHelp" placeholder="Description" value="'.$i->descr.'" /></p>';
      $html .= '<p><input type="number" name="tarif" class="form-control" aria-describedby="emailHelp" placeholder="Tarif" value="'.$i->tarif.'" /></p>';
      $html .= '<p><input type="text" name="url" class="form-control" aria-describedby="emailHelp" placeholder="lien utile" value="'.$i->url.'" /></p>';
      $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>';
      $html .= '<p><input type="file" name="image" id="image" accept=".png, .jpg, .jpeg" /></p>';
      $html .= '<p><button type="submit" class="btn btn-primary" name="supprimer_img" value="supprimer_image">Supprimer l\'image</button></p>';
      $html .= '<p><button type="submit" class="btn btn-primary" name="valider_modif" value="modifier_itesm">Valider modification</button></p>';
      $html .= '</form>';
      $html .= '</div></div>';


      $html .= '</section>';

      return array('html' => $html, 'path' => $path);
    }

    public function render($code) {

        if($code == LISTES_CREA){
            $res = $this->affichageListesCrea();

        } elseif($code == LISTES){
            $res = $this->affichageListes();

        } else if($code == LISTE_CREA){
            $res = $this->affichageListeCrea();

        } else if($code == LISTE_CO){
            $res = $this->affichageListeInvite(0);

        } else if($code == LISTE_INV){
            $res = $this->affichageListeInvite(1);

        }else if($code == 'ITEM_AJOUT'){ // Pour ajouter un item
            $res = $this->ajouterItem();
        }else if($code == 'MODIFIER'){ // Pour modifier un item
            $res = $this->modifierItem();
        }

        $content = $res['html'];
        $path = $res['path'];

        $head = '
        <title>Listes des items</title>
          <link rel="stylesheet"  href="' . $path . 'src/css/bootstrap.min.css"/>
          <link rel="stylesheet"  href="' . $path . 'src/css/itemsListes.css"/>
          <link rel="stylesheet" href="' . $path . 'src/css/principale.css">
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
          <script src="' . $path . 'src/js/itemsListes.js"></script>
          <script src="' . $path . 'src/js/bootstrap.min.js"></script>';

        if(Auth::isLogged()){
            $navBar = '
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
              <a class="navbar-brand" href="' . $this->app->urlFor('Accueil') . '">My Wish List</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
               <form class="form-inline my-2 my-md-0">
                  <input class="form-control" type="text" placeholder="Rechercher">
                </form>
              <div class="collapse navbar-collapse" id="navbarsExample04">
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item active">
                    <a class="nav-link" href="' . $this->app->urlFor('listePublic') . '">Les listes du moment <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item active">
                    <a class="nav-link" href="#">Vos contacts <span class="sr-only">(current)</span></a>
                  </li>
                <li class="nav-item active" id="compte">
                    <a class="nav-link" href="' . $this->app->urlFor('Compte') . '">Mon compte <span class="sr-only">(current)</span></a>
                  </li>
                </ul>
                </div>
                <a class="nav-item " href="#">
                    <img src="' . $path . 'src/img/profil.png" width="30" height="30" alt="">
                </a>
            </nav>
            <div class="container">
                <div class="row">
                <form method="post" action="">
                        <div  class="col col-lg-4">
                            <button type="submit" class="btn btn-primary compte" name="deconnexion">Se déconnecter</button>
                        </div>
                    </form>
                    <div class="col col-l-4">
                        <a href="' . $this->app->urlFor('Compte') . '">
                            <button class="btn btn-primary compte" >Mon compte</button></a>
                    </div>
                </div>
            </div>';
        } else{
            $navBar = '
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
              <a class="navbar-brand" href="' . $this->app->urlFor('Accueil') . '">My Wish List</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
               <form class="form-inline my-2 my-md-0">
                  <input class="form-control" type="text" placeholder="Rechercher">
                </form>
              <div class="collapse navbar-collapse" id="navbarsExample04">
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item active">
                    <a class="nav-link" href="' . $this->app->urlFor('listePublic') . '">Les listes du moment<span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item active">
                    <a class="nav-link" href="#">Découvrir <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item active">
                    <a class="nav-link" href="#">Autres <span class="sr-only">(current)</span></a>
                  </li>
                <li class="nav-item active" id="compte">
                    <a class="nav-link" href="' . $this->app->urlFor('Inscription') . '">Créer un compte <span class="sr-only">(current)</span></a>
                  </li>
                </ul>
                </div>
                <a class="nav-item " href="' . $this->app->urlFor('connexion') . '">
                    <img src="' . $path . 'src/img/profil.png" width="30" height="30" alt="">
                </a>
            </nav>';
        }


        $html = <<<END
 <html>
	<head>
        <meta charset="utf-8">
	      $head
	</head>

	<body>
            $navBar
            $content
    </body>

    <footer></footer>
 </html>
END;

        echo $html;
    }

}
