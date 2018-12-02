<?php

namespace wishlist\controleurs;

require_once 'vendor/autoload.php';

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models\Item as Item;
use \wishlist\models\Liste as Liste;

class ContAffichageListe {

    public function __construct(){}
    

    public function afficherListe($token){
        //$template = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/ProjetPHPS3/src/vues/AffichageListes.html');
        
        $html = file_get_contents($_SERVER["DOCUMENT_ROOT"].'/ProjetPHPS3/src/vues/header.html');
        
        
        // <Chargement
        
        $html .= '<ul>';
        
        $listes = Liste::where('token', 'like', $token)->get();
        
        foreach($listes as $l){
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
        
        $html .= '</ul>';
        
        // Chargement>
        
        //$html .= preg_replace_callback("/%([A-Z]+)/", array($token, 'affListe'), $template);

        $html .= file_get_contents($_SERVER['DOCUMENT_ROOT'].'/ProjetPHPS3/src/vues/AffichageListes.html');
        
        $html .= file_get_contents($_SERVER['DOCUMENT_ROOT'].'/ProjetPHPS3/src/vues/footer.html');

        
        echo $html;
    }
    
    public function afficherItemListe($id) {
        //$template = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/ProjetPHPS3/src/vues/AffichageListes.html');
        
        $html = file_get_contents($_SERVER["DOCUMENT_ROOT"].'/ProjetPHPS3/src/vues/header.html');
        
        
        // <Chargement
        
        $item = Item::where('id', '=', $id)->first();
        
        $html .= '<p>' . $item->nom . ' - ' . $item->description . ' - ' . $item->img . ' - ' . $item->tarif . '</p>';
        
        // Chargement>
        
        //$html .= preg_replace_callback("/%([A-Z]+)/", array($token, 'affListe'), $template);

        $html .= file_get_contents($_SERVER['DOCUMENT_ROOT'].'/ProjetPHPS3/src/vues/AffichageItem.html');
        
        $html .= file_get_contents($_SERVER['DOCUMENT_ROOT'].'/ProjetPHPS3/src/vues/footer.html');

        
        echo $html;
    }

}


 ?>
