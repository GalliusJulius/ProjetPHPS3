<?php

namespace wishlist\vues;


const LISTES = 1.0;
const LISTE_CREA = 2.0;
const LISTE_CO = 2.1;
const LISTE_INV = 2.2;
const ITEM = 3.0;
const RESERVER = 4.0;


class VueParticipant {
    
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
    
    
    private function affichageListes() {
        $link = "<link rel='stylesheet'  href='./src/css/bootstrap.min.css'/>
        <link rel='stylesheet'  href='./src/css/itemsListes.css'/>
        <link rel='stylesheet' href='./src/css/principale.css'>";
        $script = '<script src="./src/js/details.js"></script>
        <script src="./src/js/bootstrap.min.js"></script>';
        $imgMenu = '<img src="./src/img/profil.png" width="30" height="30" alt="">';
        
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
        
        return array('html' => $html, 'link' => $link, 'script' => $script, 'imgMenu' => $imgMenu);
    }
    
    
    private function affichageListeCrea() {
        $link = "<link rel='stylesheet'  href='../src/css/bootstrap.min.css'/>
        <link rel='stylesheet'  href='../src/css/itemsListes.css'/>
        <link rel='stylesheet' href='../src/css/principale.css'>";
        $script = '<script src="../src/js/details.js"></script>
        <script src="../src/js/bootstrap.min.js"></script>';
        $imgMenu = '<img src="../src/img/profil.png" width="30" height="30" alt="">';
        
        $html = '<section><ul class="listes">';
        $cpt = 1;
        
        foreach($this->liste as $l){
            $items = $l->items()->get();
            
            if(isset($l)){
                $html .= '<li><p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><div class="row items">';

                foreach($items as $i){
                    $html .= '<div class="col col-l-3">';
                    $html .= '<p class="nom"><h4>' . $i->nom . '</h4></p><img src="../src/img/' . $i->img . '"><p class="tarif">' . $i->tarif .  ' €</p>' . '<br/><br/>';
                    $html .= '<button class="details btn btn-primary h' . $cpt . '">Détails</button>';
                    $html .= '<form method="GET" action="' . $this->app->urlFor('itemListe', array('id' => $i->id)) . '">'; // TODO changer route
                    $html .= '<button class="btn btn-primary">Modifier</button>';
                    $html .= '</form>';
                    
                    $html .= '<section class="hidden hide' . $cpt . '"><h6 class="hidden">Description :</h6>';
                    $html .= '<p class="hidden desc">' . $i->descr . '</p>';
                    
                    if($i->url != null or $i->url != ""){
                        $html .= '<a class="hidden" target="_blank" href="' . $i->url . '>Produit disponible ici !</a>';
                    } else{
                        $html .= '<p class="hidden">Aucune URL associé !</p>';
                    }
                    
                    $html .= '</section></div>';
                    $cpt++;
                }
                $html .= '</div><p class="date">' . $l->expiration . '</p></li>';
            }
        }
        
        $html .= '</ul></section>';
        
        return array('html' => $html, 'link' => $link, 'script' => $script, 'imgMenu' => $imgMenu);
    }
    
    private function affichageListeInvite($n) { // TODO page de réservation ( + modif. sur la BDD)
        if($n == 0){
          $link = "<link rel='stylesheet'  href='../src/css/bootstrap.min.css'/>
        <link rel='stylesheet'  href='../src/css/itemsListes.css'/>
        <link rel='stylesheet' href='../src/css/principale.css'>";
            $script = '<script src="../src/js/details.js"></script>
        <script src="../src/js/bootstrap.min.js"></script>';
            $imgMenu = '<img src="../src/img/profil.png" width="30" height="30" alt="">';
        } else{
            $link = "<link rel='stylesheet'  href='../../src/css/bootstrap.min.css'/>
        <link rel='stylesheet'  href='../../src/css/itemsListes.css'/>
        <link rel='stylesheet' href='../../src/css/principale.css'>";
            $script = '<script src="../../src/js/details.js"></script>
        <script src="../../src/js/bootstrap.min.js"></script>';
            $imgMenu = '<img src="../../src/img/profil.png" width="30" height="30" alt="">';
        }
        
        
        $html = '<section><ul class="listes">';
        $cpt = 1;
        
        foreach($this->liste as $l){
            $items = $l->items()->get();
            
            if(isset($l)){
                $html .= '<li><p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><div class="row items">';

                foreach($items as $i){
                    $html .= '<div class="col col-l-3">';
                    $html .= '<p class="nom"><h4>' . $i->nom;
                    if($n == 0){
                        $html .= '</h4></p><img src="../src/img/';
                    } else{
                        $html .= '</h4></p><img src="../../src/img/';
                    }
                    $html .= $i->img . '"><p class="tarif">' . $i->tarif .  ' €</p>' . '<br/><br/>';
                    
                    $html .= '<button class="details btn btn-primary h' . $cpt . '">Détails</button>';
                    
                    if(count($i->reservations()->get()) > 0){
                        
                        $html .= '<button disabled class="btn btn-primary reserver h' . $cpt . '">Réserver</button>';
                        
                    } else{
                        
                        $html .= '<button class="btn btn-primary reserver h' . $cpt . '">Réserver</button>';
                    
                        $html .= '<section class="hidden hide' . $cpt . '"><h6 class="hidden">Description :</h6>';
                        $html .= '<p class="hidden desc">' . $i->descr . '</p>';

                        if($i->url != null or $i->url != ""){
                            $html .= '<a class="hidden" target="_blank" href="' . $i->url . '>Produit disponible ici !</a>';
                        } else{
                            $html .= '<p class="hidden">Aucune URL associé !</p>';
                        }
                        $html .= '</section>';

                        $html .= '<div class="modal h' . $cpt . '"><div class="form">';
                        $html .= '<form id="Reserv" method="POST" action="">';
                        // Route de réservation :
                        // ' . $this->app->urlFor('reserver', array('idItem' => $i->id, 'idListe' => $l->no)) . '
                        $html .= '<p>Nom de l\'item à reserver : </p><input type="text" name="nomItem" value="' . $i->nom . '" disabled>';
                        $html .= '<p>Prix de réservation : </p><input type="text" name="prix" value="' . $i->tarif . '" disabled>';
                        $html .= '<p>Votre nom : </p><input type="text" name="nom" value="">';
                        $html .= '<p>Votre prénom : </p><input type="text" name="prénom" 
                        value="">';
                        $html .= '<p>Message : </p><textarea rows="5" cols="50" type="text" name="message" value="" form="Reserv"></textarea>';

                        $html .= '<button type="submit" class="btn btn-primary confirmer h' . $cpt . '">Réserver</button>';

                        $html .= '</form>';
                        $html .= '<button class="btn btn-primary annuler h' . $cpt . '">Annuler</button>';

                        $html .= '</div>';
                        $html .= '</div>';
                        
                    }
                    
                    $html .= '</div>';
                    $cpt++;
                }
                $html .= '</div><p class="date">' . $l->expiration . '</p></li>';
            }
        }
        
        $html .= '</ul></section>';
        
        return array('html' => $html, 'link' => $link, 'script' => $script, 'imgMenu' => $imgMenu);
    }
    
    
    private function affichageItem() {
        $link = "<link rel='stylesheet'  href='../src/css/bootstrap.min.css'/>";
        $script = '<script src="../src/js/bootstrap.min.js"></script>';
        $imgMenu = '<img src="../src/img/profil.png" width="30" height="30" alt="">';
        
        $html = '<p>' . $this->item->nom . ' - ' . $this->item->description . ' - ' . $this->item->img . ' - ' . $this->item->tarif . '</p>';
        
        return array('html' => $html, 'link' => $link, 'script' => $script, 'imgMenu' => $imgMenu);
    }
    
    private function afficherReservationItem(){
        $link = "<link rel='stylesheet'  href='../src/css/bootstrap.min.css'/>";
        $script = '<script src="../src/js/bootstrap.min.js"></script>';
        $imgMenu = '<img src="../src/img/profil.png" width="30" height="30" alt="">';
        
        $html = $this->affichageItem();
        $html .= "<form method='POST' action='" . $this->app->urlFor('reserverItem', array('id' => $this->item->id)) . "'>";
        $html .= "<button type='submit'>Valider</button>";
        $html .= "</form>";

        return array('html' => $html, 'link' => $link, 'script' => $script, 'imgMenu' => $imgMenu);
    }
    
    
    public function render($code) {
        
        if($code == LISTES){
            $res = $this->affichageListes();
            
        } else if($code == LISTE_CREA){
            $res = $this->affichageListeCrea();
            
        } else if($code == LISTE_CO){
            $res = $this->affichageListeInvite(0);
            
        } else if($code == LISTE_INV){
            $res = $this->affichageListeInvite(1);
            
        } else if($code == ITEM){
            $res = $this->affichageItem();
            
        } else if($code == RESERVER){
            $res = $this->afficherReservationItem();
            
        }
        
        $content = $res['html'];
        $link = $res['link'];
        $script = $res['script'];
        $imgMenu = $res['imgMenu'];
        
        $html = <<<END
 <html>
	<head>
        <meta charset="utf-8">
	      <title>Listes des items</title>
          $link
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
          $script
	</head>
    
	<body>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
              <a class="navbar-brand" href="#">My Wish List</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
               <form class="form-inline my-2 my-md-0">
                  <input class="form-control" type="text" placeholder="Rechercher">
                </form> 
              <div class="collapse navbar-collapse" id="navbarsExample04">
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item active">
                    <a class="nav-link" href="#">Mes listes <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item active">
                    <a class="nav-link" href="#">Découvrir <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item active">
                    <a class="nav-link" href="#">Autres <span class="sr-only">(current)</span></a>
                  </li>
                <li class="nav-item active" id="compte">
                    <a class="nav-link" href="#">Mon compte <span class="sr-only">(current)</span></a>
                  </li>
                </ul>
                </div>
                <a class="nav-item " href="#">
                    $imgMenu
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
                        <a href="#">
                            <button class="btn btn-primary compte" >Mon compte</button></a>
                    </div>
                </div>
            </div>
            $content
    </body>

    <footer></footer>
 </html>
END;
        
        echo $html;
    }
}