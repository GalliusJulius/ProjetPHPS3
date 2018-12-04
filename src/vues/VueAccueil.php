<?php
namespace wishlist\vues;

class VueAccueil{
    
    public function __construct(){
    }
    
    public function render(){
        $html = <<< END
        <!DOCTYPE html>
        <html>
           <head>
            <meta charset="utf-8">
            <title>Accueil</title>
            <link rel='stylesheet'  href='../src/css/bootstrap.min.css'/>
        </head>
        <body>
            <div class="container">
                <div class="row">
                <form method="post" action="">
                        <div  class="col col-lg-4"> 
                            <button type="submit" class="btn btn-primary" name="deconnexion">Se déconnecter</button>
                        </div>
                    </form>
                    <div class="col col-l-4">
                        <a href="Compte.php">
                            <button class="btn btn-primary" >Mon compte</button></a>
                    </div>
                </div>
            </div>
        </body> 
     </html>
END;
        echo $html;
    }
}