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
            <meta charset="utf-8">
            <title>Gestion Items</title>
            <link rel='stylesheet'  href='../src/css/bootstrap.min.css'/>
        </head>
        <body>
            <div class="container">

                $body

            </div>
        </body>
     </html>
END;
        echo $html;
    }


    private function afficher(){
      $id = $this->item['id'];
      $nom = $this->item['nom'];
      $descr = $this->item['descr'];
      $img = $this->item['img'];
      $bod = <<<END
      <div>
        <h3> Item </h3>
        <p><strong>id</strong> = $id </p>
        <p><strong>nom</strong> = $nom </p>
        <p><strong>descr</strong> = $descr </p>
        <p><strong>img</strong> = $img </p>
      </div>
END;
      return $bod;
    }
}
