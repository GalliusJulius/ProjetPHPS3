<?php
namespace wishlist\vues;

class VueContItem{

    private $item,$select;

    public function __construct($i, $s){
      $this->item = $i;
      $this->select = $s;
    }

    public function render(){
    switch($this->select){
      case 'AFFICHAGE' : {
        $body = $this->afficher();
        break;
      }
    }
        $html = <<< END
        <!DOCTYPE html>
        <html>
           <head>
            <meta charset='utf-8'/>
            <title>Gestion Items</title>
            <link rel='stylesheet' href="../src/css/grid.css" />
            <link rel='stylesheet' href="../src/css/bootstrap.min.css" />

          </head>
        <body>
          $body
        </body>
     </html>
END;
        echo $html;
    }


    private function afficher(){
      $nom = $this->item['nom'];
      $descr = $this->item['descr'];
      $img = $this->item['img'];
      $tarif = $this->item['tarif'];
      $bod = <<<END
     <div class="row">
         <div class="col-md-2">
             <img src="../img/$img" class="rounded float-left img-fluid img-thumbnail" height="70%" width="70%" alt="">
         </div>
         <div class="col-md-8">
             <p>$nom</p>
           <div class="row">
             <div class="col-md-9">
                 $descr
             </div>
             <div class="col-md-3">
                 <p>$tarif</p>
             </div>
           </div>
             <form>
                 <button class="btn btn-primary" >ajouter</button>
             </form>
         </div>
       </div>
END;
      return $bod;
    }
}
