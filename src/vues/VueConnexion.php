<?php
namespace wishlist\vues;

class VueConnexion{
    
    public function __construct(){
    }
    
    public function render($erreur){
        $e1="";
        $e2="";
        switch($erreur){
            case "ER_CONNEXION" :{
                $e2="<p class=\"erreur\">Mail ou mot de passe incorrect</p>";
                break;
            }
            case "ER_INSCRIPTION1" :{
                $e1="<p class=\"erreur\">Les mots de passes ne sont pas les mêmes</p>";
                break;
            }
            case "ER_INSCRIPTION2" :{
                $e1="<p class=\"erreur\">Mail non disponible</p>";
                break;
            }
        }
        
        $html =  <<< END
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                  <title>Connexion/Inscription</title>
                <link rel='stylesheet'  href='src/css/Generique.css'/>
                <link rel='stylesheet'  href='src/css/bootstrap.min.css'/>
            </head>
            <body>
                <div class="container">
                    <div class="row justify-content-md-center">
                        <div class="col col-lg-4">
                            <form method="post" action="">
                                <p>
                                    <input type="text" name="prenom" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Prénom" required/>
                                </p>
                                <p>
                                    <input type="text" name="nom" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Nom" required/>
                                </p>
                                <p>
                                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Adresse mail" required/>
                                </p>
                                <p>
                                   <input type="text" name="pseudo" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Pseudo" required/>
                                </p>
                                <p>
                                    <input type="password" name="mdp" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Mot de passe" required/>
                                </p>
                                <p>
                                    <input type="password" name="mdpc" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Confirmez mot de passe" required/>
                                </p>
                                $e1
                                <p>
                                    <button type="submit" class="btn btn-primary" name="inscription" vale="inscription">Inscription</button>
                                </p>
                            </form>
                        </div>
                        <div class = "col-lg-3">
                            <form method="post" action="">
                             <p>
                                    <input type="email" name="mail" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Votre adresse mail" required/>
                                </p>
                                <p>
                                   <input type="password" name="pass" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Mot de passe" required/>
                                </p>
                                $e2
                                <p>
                                    <button type="submit" class="btn btn-primary" name="connexion" value="connec">Connexion</button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </body> 
        </html>
END;
        echo $html;
    }   
}