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
                $html .= '<p>Nombre de réservations : ' . count($l->reservations()->get()) . '</p>';
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
                $html .= '<p>Nombre de réservations : ' . count($l->reservations()->get()) . '</p>';
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
                $reservs = $i->reservations()->get();
                
                if(count($reservs) > 0){
                    $html .= '<div class="reserve col col-l-3">';
                } else{
                    $html .= '<div class="col col-l-3">';
                }
                $html .= '<p class="nom"><h4>' . $i->nom . '</h4></p><img src="../src/img/' . $i->img . '"><p class="tarif">' . $i->tarif .  ' €</p>' . '<br/><br/>';
                
                
                if(count($reservs) > 0){
                    
                    $html .= '<p>Cet item a été réservé !</p>';
                    $html .= '<button class="details btn btn-primary h' . $cpt . '">Détails</button>';
                    $html .= '<button class="message btn btn-primary h' . $cpt . '">Voir le(s) message(s)</button>';
                    
                } else{
                    $html .= '<button class="details btn btn-primary h' . $cpt . '">Détails</button>';
                    $html .= '<form method="GET" action="#">'; // TODO mettre route **TRISTAN**
                    $html .= '<button class="btn btn-primary">Modifier</button>';
                    $html .= '</form>';
                
                    $html .= '<form method="GET" action="#">'; // TODO mettre route **TRISTAN**
                    $html .= '<button class="btn btn-primary">Supprimer</button>';
                    $html .= '</form>';
                }
                

                $html .= '<section class="details hidden hide' . $cpt . '"><h6 class="hidden">Description :</h6>';
                $html .= '<p class="hidden desc">' . $i->descr . '</p>';

                if($i->url != null or $i->url != ""){
                    $html .= '<a class="hidden" target="_blank" href="' . $i->url . '>Produit disponible ici !</a>';
                } else{
                    $html .= '<p class="hidden">Aucune URL associé !</p>';
                }

                $html .= '</section>';
                
                
                if(count($reservs) > 0){
                    $html .= '<section class="message hidden hide' . $cpt . '">';
                    
                    if(count($reservs) == 1){
                        $html .= '<h6>Message :</h6>';
                    } else{
                        $html .= '<h6>Messages :</h6>';
                    }
                    
                    $html .= '<ul class="message">';

                    foreach($reservs as $r){
                        $html .= '<li>' . $r->message . '</li>';
                    }

                    $html .= '</ul><section>';
                    
                }
                
                $html .= '</div>';
                
                
                $cpt++;
            }
            
            $html .= '</div>';
                
        }
        
        $html .= '<form method="GET" action="#">'; // TODO mettre route **TRISTAN**
        $html .= '<button class="btn btn-primary">Ajouter un item</button>';
        $html .= '</form>';
            
        $html .= '<p class="date">Date d\'échéance :</p><p class="date">' . $l->expiration . '</p>';
        
        $html .= '</section>';
        
        return array('html' => $html, 'path' => $path);
    }
    
    private function affichageListeInvite($n) { // TODO page de réservation ( + modif. sur la BDD)
        if($n == 0){
            $path = '../../';
        } else{
            $path = '../../../';
        }
        
        
        $html = '<section><ul class="listes">';
        $cpt = 1;
        
        $l = $this->liste;

        if(isset($l)){
            $items = $l->items()->get();
            
            $html .= '<li><p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><div class="row items">';

            foreach($items as $i){
                
                $reservs = $i->reservations()->get();
                
                if(count($reservs) > 0){
                    $html .= '<div class="reserve col col-l-3">';
                } else{
                    $html .= '<div class="col col-l-3">';
                }
                
                $html .= '<p class="nom"><h4>' . $i->nom;
                
                if($n == 0){
                    $html .= '</h4></p><img src="../../src/img/';
                } else{
                    $html .= '</h4></p><img src="../../../src/img/';
                }
                
                $html .= $i->img . '"><p class="tarif">' . $i->tarif .  ' €</p>' . '<br/><br/>';

                $html .= '<button class="details btn btn-primary h' . $cpt . '">Détails</button>';
                

                if(count($reservs) > 0){

                    $html .= '<button disabled class="btn btn-primary reserver h' . $cpt . '">Réserver</button>';

                    $html .= '<p>Cette item a déjà été réservé par :</p>';
                    $html .= '<ul class="reserv">';

                    foreach($reservs as $r){
                        $html .= '<li>' . $r->prénom . ' ' . $r->nom . '</li>';
                    }

                    $html .= '</ul>';

                } else{

                    $html .= '<button class="btn btn-primary reserver h' . $cpt . '">Réserver</button>';

                    $html .= '<div class="modal h' . $cpt . '"><div class="form">';
                    $html .= '<form id="Reserv" method="POST" action="' . $this->app->urlFor('reserver', array('share' => $l->share, 'idItem' => $i->id)) . '">';
                    $html .= '<p>Nom de l\'item à reserver : </p><input type="text" name="nomItem" value="' . $i->nom . '" disabled>';
                    $html .= '<p>Prix de réservation : </p><input type="text" name="prix" value="' . $i->tarif . '" disabled>';

                    $n = ''; $p = '';
                    $idUser = Auth::getIdUser();
                    
                    if(isset($idUser)){
                        $m = Membre::where('idUser', '=', $idUser)->first();
                        $n = $m->Nom;
                        $p = $m->Prénom;
                    }

                    $html .= '<p>Votre nom : </p><input type="text" name="nom" value="' . $n . '">';
                    $html .= '<p>Votre prénom : </p><input type="text" name="prénom" 
                    value="' . $p . '">';
                    $html .= '<p>Message : </p><textarea rows="5" cols="50" type="text" name="message" value="" form="Reserv"></textarea>';

                    $html .= '<button type="submit" class="btn btn-primary confirmer h' . $cpt . '">Réserver</button>';

                    $html .= '</form>';
                    $html .= '<button class="btn btn-primary annuler h' . $cpt . '">Annuler</button>';

                    $html .= '</div>';
                    $html .= '</div>';

                }

                $html .= '<section class="hidden hide' . $cpt . '"><h6 class="hidden">Description :</h6>';
                $html .= '<p class="hidden desc">' . $i->descr . '</p>';

                if($i->url != null or $i->url != ""){
                    $html .= '<a class="hidden" target="_blank" href="' . $i->url . '>Produit disponible ici !</a>';
                } else{
                    $html .= '<p class="hidden">Aucune URL associé !</p>';
                }
                $html .= '</section>';


                $html .= '</div>';
                $cpt++;
            }
            $html .= '</div><p class="date">' . $l->expiration . '</p></li>';
        }
        
        $html .= '</ul></section>';
        
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
                    <a class="nav-link" href="' . $this->app->urlFor('liste') . '">Mes listes <span class="sr-only">(current)</span></a>
                  </li>
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