<?php
namespace wishlist\vues;

class VueAccueil{
    private $typePage;
    private $messageErreur;

    
    public function __construct($type,$erreur){
        $this->typePage=$type;
        $this->messageErreur=$erreur;
    }
    
    public function render(){
        $app = \Slim\Slim::getInstance();
        $contenu = "<h1>ERREUR</h1>";
        $style="";
        switch($this->typePage){
            case "accueil":{
                $contenu = $this->accueil();
                break;
            }
            case "compte":{
                if($this->messageErreur!=""){
                    $this->messageErreur="<p class=\"erreur\">$this->messageErreur</p>";
                }
                $contenu = $this->monCompte();
                $style = "<link rel=\"stylesheet\" href=\"../src/css/monCompte.css\">"; 
                break;
            }
                
        }
        $lienAccueil = $app->urlFor('accueil');
        $lienCompte = $app->urlFor('Compte');
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
    $style
  </head>

  <body>
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
              <a class="navbar-brand" href="$lienAccueil">My Wish List</a>
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
                    <a class="nav-link" href=$lienCompte>Mon compte <span class="sr-only">(current)</span></a>
                  </li>
                </ul>
                </div>
                <a class="nav-item " href=$lienCompte>
                    <img src="../src/img/profil.png" width="30" height="30" alt="">
                </a>
            </nav>
            
            <div class="container">
                $contenu
            </div>
            
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="../src/js/bootstrap.min.js"></script>
        </body> 
     </html>
END;
        echo $html;
    }
    
    public function accueil(){
        $app = \Slim\Slim::getInstance();
        $lien = $app->urlFor('Compte');
        $html = <<< END
                <div class="row">
                <form method="post" action="">
                        <div  class="col col-lg-4"> 
                            <a href="">
                                <button type="submit" class="btn btn-primary" name="deconnexion">Se déconnecter</button>
                            </a>
                        </div>
                    </form>
                </div>
        
END;
        return $html;
    }
    
    public function monCompte(){
        $app = \Slim\Slim::getInstance();
        $lienAccueil = $app->urlFor('accueil');
        #Permet la modification dynamique
        try{ session_start();}
        catch(\Exception $e){}
        $html= <<<END
            <div class="row justify-content-md-center">
            <div class="col col-lg-4 justify-content-md-center">
                <form method="post" class="text-center">
END;
        foreach($_SESSION['profil'] as $key=>$val){
            $temp = <<<END
            <p>$key actuel : $val<input type="text" name="$key" class="form-control" placeholder="Nouveau $key"></p>
END;
             $html = $html . $temp;
        }
        $fin = <<<END
         <div id = "modifMdp"class="row justify-content-md-center">
                                 <p> Changer son mot de passe
                                <input type="password" name ="mdp" class="form-control" placeholder="Nouveau mot de passe">
                                <input type="password" name="mdpc" class="form-control" placeholder="Confirmation">
                                </p>
                            </div>
                            <a href=$lienAccueil>
                                <label class="btn btn-secondary">Annuler</label>
                            </a>
                                <button type="submit" class="btn btn-primary" name="valider" value="validation">Effectuer les modifications</button>
                                $this->messageErreur
                        </form>
                    </div>
                </div>
END;
        $html=$html.$fin;
        return $html;
        
    }
}