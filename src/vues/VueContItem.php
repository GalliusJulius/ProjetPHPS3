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
      case 'MODIFIER' : {
        $body = $this->modifier();
      }
    }
        $html = <<< END
        <!DOCTYPE html>
          $body
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
      $tarif = $this->item['tarif'];
      $bod = <<<END
      <html>
         <head>
         <meta charset='utf-8' />
         <link rel='stylesheet' href='../src/css/bootstrap.min.css' />
         <link rel='stylesheet' href='../src/css/grid.css' />
        </head>
      <body>
     <div class="row">
         <div class="col-md-2">
             <img src="../src/img/$img" class="rounded float-left img-fluid img-thumbnail" height="100%" width="100%" alt="">
         </div>
        <div class="col-md-6">
             $nom
           <div class="row">
             <div class="col-md-9">
                 $descr
             </div>
             <div class="col-md-3">
                 <p>$tarif</p>
             </div>
           </div>
            <form method="post" action="/Projet/projet/test/$id/modifier">
              <button type="submit" class="btn btn-primary" name="modifier">Modifier</button>
           </form>
           <form method="post" action="/Projet/projet/test/$id/ajouter">
             <button type="submit" class="btn btn-primary" name="ajouter">Ajouter</button>
          </form>
          <form method="post" action="/Projet/projet/test/$id/supprimer">
            <button type="submit" class="btn btn-primary" name="supprimer">Supprimer</button>
         </form>
         </div>
       </div>
END;
      return $bod;
    }
// pour les paramétres les passer dans le lien transmit par le bouton
    private function modifier(){
      $id = $this->item['id'];
      $img = $this->item['img'];
      $bod = <<<END
      <html>
         <head>
         <meta charset='utf-8' />
         <link rel='stylesheet' href='../../src/css/bootstrap.min.css' />
         <link rel='stylesheet' href='../../src/css/grid.css' />
        </head>
      <body>
      <div class="row">
          <div class="col-md-2">
              <img src="../../src/img/$img" class="rounded float-left img-fluid img-thumbnail" height="100%" width="100%" alt="">
          </div>
         <div class="col-md-6">
         <form method="post" action="/Projet/projet/test/$id">
           <p><input type="text" name="nom" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Nom" required/></p>
            <div class="row">
              <div class="col-md-9">
                <p><input type="text" name="descr" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Description" required/></p>
              </div>
              <div class="col-md-3">
                <p><input type="number" name="tarif" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Tarif" required/></p>
              </div>
                <button type="submit" class="btn btn-primary" name="valider">Valider</button>
             </form>
            </div>
          </div>
END;
      return $bod;
    }
}
