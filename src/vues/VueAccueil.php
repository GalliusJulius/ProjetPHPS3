<?php
namespace wishlist\vues;

class VueAccueil{
    private $typePage;
    private $messageErreur;
    private $tableau;
    
    public function __construct($type,$erreur,$tab=array()){
        $this->typePage=$type;
        $this->messageErreur=$erreur;
        $this->tableau = $tab;
    }
    
    public function render(){
        $app = \Slim\Slim::getInstance();
        $contenu = "<h1>ERREUR</h1>";
        $style="";
        $path="";
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
                $style = "<link rel=\"stylesheet\" href=\"./src/css/monCompte.css\">"; 
                break;
            }
            case "suppCompte":{
                $contenu = $this->supprimerCompte();
                break;    
            }
            case "confSupp":{
                $contenu = $this->confSupp();
                break;
            }
            case "mesListes":{
                $contenu = $this->mesListes();
                break;
            }
            case "createurs":{
                $contenu = $this->createurs();
                break;
            }
            case "visionComptes":{
                $contenu = $this->visionComptes();
                $path=".";
                break;
            }
                
        }
        $lienAccueil = $app->urlFor('accueil');
        $lienCompte = $app->urlFor('Compte');
        $lienMesListes = $app->urlFor('mesListes');
        $lienListesPublic = $app->urlFor('listePublic');
        $lienCreateur = $app->urlFor('createur');
        $html = <<< END
        <!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../favicon.ico">

    <title>Navbar Template for Bootstrap</title>
    <link rel="stylesheet" href="$path./src/css/bootstrap.min.css">
    <link rel="stylesheet" href="$path./src/css/principale.css">
    $style
  </head>

  <body>
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
              <a class="navbar-brand" href="$lienAccueil">MyWishList</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
               <form class="form-inline my-2 my-md-0">
                  <input class="form-control" type="text" placeholder="Rechercher">
                </form> 
              <div class="collapse navbar-collapse" id="navbarsExample04">
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item active">
                    <a class="nav-link" href=$lienMesListes>Mes listes <span class="sr-only">(current)</span></a>
                </li>
                  <li class="nav-item active">
                    <a class="nav-link" href=$lienListesPublic>Les listes du moment <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item active">
                    <a class="nav-link" href=$lienCreateur>Listes créateurs<span class="sr-only">(current)</span></a>
                  </li>
                <li class="nav-item active" id="compte">
                    <a class="nav-link" href=$lienCompte>Mon compte <span class="sr-only">(current)</span></a>
                  </li>
                </ul>
                </div>
                <a class="nav-item " href=$lienCompte>
                    <img src="$path./src/img/profil.png" width="30" height="30" alt="">
                </a>
            </nav>
            
            <div class="container">
                $contenu
            </div>
            
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="./src/js/bootstrap.min.js"></script>
        </body> 
     </html>
END;
        echo $html;
    }
    
    public function supprimerCompte(){
        $app = \Slim\Slim::getInstance();
        $lienAccueil = $app->urlFor('accueil');
        $cont = <<< END
        <div class="row justify-content-md-center">
            <div class="col col-lg-7 justify-content-md-center">
                <h1>Supprimer son compte</h1>
                <p>Vous êtes sur le point de supprimer votre compte, si vous confirmez cette suppression toutes vos informations personnelles, listes et toutes informations que notre application possède sur vous sera définitivement supprimé. En cliquant sur le bouton ci-dessus vous l'acceptez, cette action est définitive.</p>
                <form method="post" action="">
                            <a href=$lienAccueil>
                                <label class="btn btn-secondary">Annuler</label>
                            </a>
                             <a href="">
                                <button type="submit" class="btn btn-primary" name="suppression">Supprimer votre compte</button>
                              </a>
                </form>
            </div>
        </div>
        
END;
        return $cont;
            
    } 
    
    public function confSupp(){
        $app = \Slim\Slim::getInstance();
        $lienAccueil = $app->urlFor('connexion');
        $cont = <<< END
        <div class="row justify-content-md-center">
            <div class="col col-lg-7 justify-content-md-center">
                <h1>Confirmation</h1>
                <p>Votre compte a bien été supprimé, nous espérons vous revoir bientot!</p>
                     <a href=$lienAccueil>
                             <button class="btn btn-primary" name="suppression">Retour page de connexion</button>
                        </a>
                </form>
            </div>
        </div>
END;
        return $cont;
        
    }
    
    public function accueil(){
        $app = \Slim\Slim::getInstance();
        $lien = $app->urlFor('Compte');
        $html = <<< END
                <div class="row">
                <form method="post" action="">
                        <div  class="col col-lg-4"> 
                                <button type="submit" class="btn btn-primary" name="deconnexion">Se déconnecter</button>
                        </div>
                    </form>
                </div>
        
END;
        return $html;
    }
    
    public function monCompte(){
        $app = \Slim\Slim::getInstance();
        $lienAccueil = $app->urlFor('accueil');
        $lienSupp = $app->urlFor('suppCompte');
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
            <p>$key actuel : $val</p><input type="text" name="$key" class="form-control" placeholder="Nouveau $key">
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
                            $this->messageErreur
                            <a href=$lienAccueil>
                                <label class="btn btn-secondary">Annuler</label>
                            </a>
                                <button type="submit" class="btn btn-primary" name="valider" value="validation">Effectuer les modifications</button>
                            <a href=$lienSupp>
                                <label class="btn btn-danger">Supprimer le compte</label>
                            </a>
                        </form>
                    </div>
                </div>
END;
        $html=$html.$fin;
        return $html;
        
    }
    
    public function mesListes(){
        $html = <<<END
        <div class="row justify-content-md-center">
            <div class="col col-lg-12 justify-content-md-center">
            <h1>Les listes que vous avez créé:</h1>
END;
        $i=0;
        $app = \Slim\Slim::getInstance();
        foreach($this->tableau[0] as $val){
              $lien = $app->urlFor('listeCrea',['token'=>$val->token]);
            $i++;
            $modifierListe = $app->urlFor('modifierListe', array('token' => $val->token));
            $supprimerListe = $app->urlFor('supprimer_liste', array('token' => $val->token));
            $html .= '<div class="row">';
            $html .= '<div class="col col-lg-6 ">';
            $html .= '<h2><b>'.$i.' : </b><a href = $lien  >'.$val->titre.'</a><h2>';
            $html .= '</div>';
            $html .= '<div class="col col-lg-3">';
            $html .= '<form method="GET" action= "'.$modifierListe.'">';
            $html .= '<button class="btn btn-primary col col-lg-6" value="modifierListe">Modifier liste</button>';
            $html .= "</form>";
            $html .= '</div>';
            $html .= '<div class="col col-lg-3">';
            $html .= '<form method="GET" action= "'.$supprimerListe.'" >';
            $html .= '<button class="btn btn-primary col col-lg-6" value="supprimerListe">Supprimer liste</button>';
            $html .= "</form>";
            $html .= '</div>';
            $html .= "</div>";
        }
        if($i==0){
            $html .= "<h3> vous n'avez pas encore créé de listes!</h3>";  
        }
		$creerListe = $app->urlFor('creerListe');
        $html .= <<<END
        <div class="row">
            <div class="col col-lg-6">
                <p>Vous voulez créer une liste?</p>
            </div>
            <div class="col col-lg-6">
              <form method="GET" action= "$creerListe">
                <button class="btn btn-primary col col-lg-3" value="creerListe">Créer listes</button>
              </form>
            </div>
        </div>
        <h1>Les listes qu'on vous a partagé :</h1>
END;
        $i=0;
        foreach($this->tableau[1] as $val){
            $lien = $app->urlFor('listeCrea',['token'=>$val->token]);
            $i++;
            $html .=  "<div class =\"col-lg-8\"><h2><b>$i : </b><a href = $lien  >$val->titre</a><h2>" . "</div><div class =\"col-lg-2\"><form method=\"post\"><button type=\"submit\" class=\"btn btn-danger\" name=\"suppression\" value=$val->token>Supprimer</button></form></div>"; 
        }
        if($i==0){
            $html .= "</div><h3> vous n'avez pas encore ajouté de listes de vos amis, vous pouvez en créer une !</h3>";  
        }
        $html .= <<<END
        <div class="row justify-content-md-center">
        <div class="col col-lg-6 justify-content-md-center">
        <p>Ajouter la liste d'un de vos amis? Remplissez le token de sa liste dans le champ prévu et cliquez sur ok</p>
         </div>
         <div class="col col-lg-6 justify-content-md-center">
            <form method="post" class="text-center">
                <input type="text" name="token" class="form-control" placeholder="Token liste">
                <button type="submit" class="btn btn-primary" name="ajout" value="add">Ajouter</button>
            </form>
            <p class="">$this->messageErreur</p>
            </div>
        </div>
        </div>
        </div>
END;
        return $html;
    }
    
    public function visionComptes(){
        $app = \Slim\Slim::getInstance();
        $perso = $this->tableau[0];
        $amis = $this->tableau[1];
        $liste = $this->tableau[2];
        $contenu = <<<END
        <div class="row justify-content-md-center">
            <div class="col">
                <h1>$perso->Pseudo : #$perso->idUser</h1>
                <img src="../src/img/profil.png" width="150" height="150">
                <p>Message d'humeur :</p>
                <h2>Ses listes:</h2>
END;
        $btn = "";
        if(!isset($amis)){
            $btn = "<form method=\"POST\">
                <button class=\"btn btn-primary\">Ajouter à sa liste d'ami</button>
            </form>";
        }
        else{
            if($amis->statut == "Attente"){
                $btn = "<h3>En attente de validation</h3>";
            }
            else{
                $btn = "<h3>Vous etes déjà amis</h3>";
            }
        }
        $contenu.=$btn;
        $listes ="";
        if(count($liste)==0){
            $listes="<p>Cet utilisateur n'a pas crée de listes</p>";
        }
        else{
            $i=0;
            foreach($liste as $val){
                $lien = $app->urlFor('listeCrea',['token'=>$val->token]);
                $i++;
                $listes .=
                "<div class=\"col col-lg-6 \">
                <h2><b>$i : </b><a href = $lien>$val->titre</a><h2>
                </div>";       
            }
        }
        $contenu.=$listes;
        $contenu .="</div></div>";
        return $contenu;
    }
    
    public function createurs(){
        $contenu = "<div class=\"row\">";
        foreach($this->tableau as $val){
            $personne =$val[0];
            $contenu .= "<div class=\"col-lg-6\"><h2>$personne->Pseudo</h2><p>Ce créateur n'a pas de messages d'humeurs</p><p>Il a créé : $val[1] liste(s)</div>";
        }
        $contenu .="</div>";
        return $contenu;
    }
}