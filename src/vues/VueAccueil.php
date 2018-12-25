<?php
namespace wishlist\vues;

class VueAccueil{
    private $lienCompte="";
    
    public function __construct(){
        $app = \Slim\Slim::getInstance();
        $this->lienCompte = $app->urlFor('Compte');
    }
    
    public function render(){
        $html = <<< END
        <!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Navbar Template for Bootstrap</title>
    <link rel="stylesheet" href="../src/css/bootstrap.min.css">
    <link rel="stylesheet" href="../src/css/principale.css">
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
                    <img src="../profil.png" width="30" height="30" alt="">
                </a>
            </nav>
            <div class="container">
                <div class="row">
                <form method="post" action="">
                        <div  class="col col-lg-4"> 
                            <button type="submit" class="btn btn-primary" name="deconnexion">Se déconnecter</button>
                        </div>
                    </form>
                    <div class="col col-l-4">
                        <a href=$this->lienCompte>
                            <button class="btn btn-primary" >Mon compte</button></a>
                    </div>
                </div>
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="../src/js/bootstrap.min.js"></script>
        </body> 
     </html>
END;
        echo $html;
    }
}