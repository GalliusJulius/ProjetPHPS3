<?php

namespace wishlist\vues;

class VueParticipant {
    
    private $liste, $item;
    
    public function __construct($tab) {
        if(isset($tab['liste'])){
            $this->liste = $tab['liste'];
        }
        
        if(isset($tab['item'])){
            $this->item = $tab['item'];
        }
    }
    
    
    private function affichageListes() {
        
        $html = '<section><ul>';
        
        foreach($this->liste as $l){
            $items = $l->items()->get();
            
            // TODO mettre des liens
            
            if(isset($l)){
                $html .= '<li><p>' . $l->titre . '</p><p>' . $l->description . '</p><p>' . $l->expiration . '</p>';
                
                $html .= '</li>';
            }
        }
        
        $html .= '</ul></section>';
        
        return $html;
    }
    
    
    private function affichageListe() {
        
        $html = '<section><ul>';
        
        foreach($this->liste as $l){
            $items = $l->items()->get();
            
            // TODO mettre des liens
            
            if(isset($l)){
                $html .= '<li><p>' . $l->titre . '</p><p>' . $l->description . '</p><p>' . $l->expiration . '</p><ul>';

                foreach($items as $i){
                    $html .= '<li><p>' . $i->nom . ' - ' . $i->img . ' - ' . $i->tarif .  '</p></li>' . '<br/><br/>';
                }
                $html .= '</ul></li>';
            }
        }
        
        $html .= '</ul></section>';
        
        return $html;
    }
    
    
    private function affichageItem() {
        
        $html = '<p>' . $this->item->nom . ' - ' . $this->item->description . ' - ' . $this->item->img . ' - ' . $this->item->tarif . '</p>';
        
        return $html;
    }
    
    
    public function render($code) {
        $content = "";
        
        if($code == 1){
            $content = $this->affichageListes();
        } else if($code == 2){
            $content = $this->affichageListe();
        } else if($code == 3){
            $content = $this->affichageItem();
        }
        
        $html = <<<END
 <html>
	<head>
        <meta charset="utf-8">
	      <title>Listes des items</title>
        <link rel='stylesheet'  href='src/css/bootstrap.min.css'/>
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