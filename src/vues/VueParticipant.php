<?php

namespace wishlist\vues;

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
        <link rel='stylesheet'  href='./src/css/grid.css'/>
        <link rel='stylesheet'  href='./src/css/itemsListes.css'/>";
        
        $html = '<section><ul class="listes">';
        
        foreach($this->liste as $l){
            $items = $l->items()->get();
            
            if(isset($l)){
                $html .= '<li><p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><p class="date">' . $l->expiration . '</p>';
                $html .= '<form method="GET" action="' . $this->app->urlFor('liste', array('token' => $l->token)) . '">';
                $html .= '<button class="btn btn-primary">Détails</button>';
                $html .= '</li>';
                $html .= "</form>";
            }
        }
        
        $html .= '</ul></section>';
        
        return array('html' => $html, 'link' => $link);
    }
    
    
    private function affichageListe() {
        $link = "<link rel='stylesheet'  href='../src/css/bootstrap.min.css'/>
        <link rel='stylesheet'  href='../src/css/grid.css'/>
        <link rel='stylesheet'  href='../src/css/itemsListes.css'/>";
        
        $html = '<section><ul class="listes">';
        
        foreach($this->liste as $l){
            $items = $l->items()->get();
            
            if(isset($l)){
                $html .= '<li><p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><div class="row items">';

                foreach($items as $i){
                    $html .= '<div class="col col-l-3">';
                    $html .= '<p class="nom"><h4>' . $i->nom . '</h4></p><img src="../src/img/' . $i->img . '"><p class="tarif">' . $i->tarif .  ' €</p>' . '<br/><br/>';
                    $html .= '<form method="GET" action="' . $this->app->urlFor('itemListe', array('id' => $i->id)) . '">';
                    $html .= '<button class="btn btn-primary">Détails</button>';
                    $html .= "</form>";
                    $html .= '</div>';
                }
                $html .= '</div><p class="date">' . $l->expiration . '</p></li>';
            }
        }
        
        $html .= '</ul></section>';
        
        return array('html' => $html, 'link' => $link);
    }
    
    
    private function affichageItem() {
        $link = "<link rel='stylesheet'  href='../src/css/bootstrap.min.css'/>";
        
        $html = '<p>' . $this->item->nom . ' - ' . $this->item->description . ' - ' . $this->item->img . ' - ' . $this->item->tarif . '</p>';
        
        return array('html' => $html, 'link' => $link);
    }
    
    private function afficherReservationItem(){
        $link = "<link rel='stylesheet'  href='../src/css/bootstrap.min.css'/>";
        
        $html = $this->affichageItem();
        $html .= "<form method='POST' action='" . $this->app->urlFor('reserverItem', array('id' => $this->item->id)) . "'>";
        $html .= "<button type='submit'>Valider</button>";
        $html .= "</form>";

        return array('html' => $html, 'link' => $link);
    }
    
    
    public function render($code) {
        $content = "";
        
        if($code == 1){
            $res = $this->affichageListes();
            $content = $res['html'];
            $link = $res['link'];
        } else if($code == 2){
            $res = $this->affichageListe();
            $content = $res['html'];
            $link = $res['link'];
        } else if($code == 3){
            $res = $this->affichageItem();
            $content = $res['html'];
            $link = $res['link'];
        } else if($code == 4){
            $res = $this->afficherReservationItem();
            $content = $res['html'];
            $link = $res['link'];
        }
        
        $html = <<<END
 <html>
	<head>
        <meta charset="utf-8">
	      <title>Listes des items</title>
          $link
	</head>
	<body>
    $content
    </body>

    <footer></footer>


 </html>
END;
        
        echo $html;
    }
}