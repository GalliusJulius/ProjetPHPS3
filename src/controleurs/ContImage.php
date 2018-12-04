<?php

namespace wishlist\controleurs;

use \Illuminate\Database\Capsule\Manager as DB;
use \wishlist\models as m;

class ContImage {
 
    public function ajouterImage($id, $îmg) {
        $item = m\Item::where("id", "=", $id)->get();
        $item->img = $img;
        $item->save();
    }
    
    public function modifierImage($id, $img) {
        $this->ajouterImage();
    }
    
    public function supprimerImage() {
        $item = m\Item::where("id", "=", $id)->get();
        $item->img = "";
        $item->save();
    }
    
    public function uploaderImage() {
        
    }
}