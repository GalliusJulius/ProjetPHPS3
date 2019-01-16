<?php
namespace wishlist\vues;

use \wishlist\Auth\Authentification as Auth;
use \wishlist\models\Membre;

class VueWebSite{
    
    private $liste, $listePart, $item, $membre, $amis, $demande, $recherche, $messageErreur, $typeErreur, $app;
    
    public function __construct($tab = array()){
        
        if(isset($tab['liste'])){
            $this->liste = $tab['liste'];
        }
        
        if(isset($tab['listePartagee'])){
            $this->listePart = $tab['listePartagee'];
        }

        if(isset($tab['item'])){
            $this->item = $tab['item'];
        }
        
        if(isset($tab['membre'])){
            $this->membre = $tab['membre'];
        }
        
        if(isset($tab['amis'])){
            $this->amis = $tab['amis'];
        }
        
        if(isset($tab['recherche'])){
            $this->recherche = $tab['recherche'];
        }
        
        if(isset($tab['erreur'])){
            $this->erreur = $tab['erreur'];
        }
        
        if(isset($tab['demande'])){
            $this->demande = $tab['demande'];
        }
        
        if(isset($_SESSION['messageErreur']) and isset($_SESSION['typeErreur'])){
            $this->messageErreur = $_SESSION['messageErreur'];
            $this->typeErreur = $_SESSION['typeErreur'];
            $_SESSION['messageErreur'] = NULL;
            $_SESSION['typeErreur'] = NULL;
        }

        $this->app = \Slim\Slim::getInstance();
    }
    
    public function supprimerCompte(){
        $lienAccueil = $this->app->urlFor('accueil');
        
        $html = <<< END
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
        
        return $html;
            
    } 
    
    public function confSupp(){
        $lienAccueil = $this->app->urlFor('connexion');
        
        $html = <<< END
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
        
        return $html;
        
    }
    
    public function accueil(){
        $lien = $this->app->urlFor('Compte');
        if(Auth::isLogged()){
            $pseudo = $_SESSION['profil']['Pseudo'];
        }
        else{
            $pseudo ="inconnu";
        }
        
        $html = <<< END
        <div id ="top" class="position-relative overflow-hidden  p-3 p-md-5  text-center bg-light">
              <div class="col-md-5 p-lg-5 mx-auto my-5">
                    <h1 class="display-4 font-weight-normal">Bienvenue sur WishList $pseudo !</h1>
                    <p class="lead font-weight-normal">Vous pouvez sur notre application créer des listes de cadeaux, participer et consulter celles de vos amis et bien d'autres choses! Sur cette page vous retrouverez un pannel de ce que vous pouvez faire ici!</p>
              </div>
        </div>
        
        <div class="flex-md-equal w-100">
            <div class="articles bg-dark text-center text-white overflow-hidden">
            <div>
              <h2 class="display-5">Vous pouvez créer une liste</h2>
              <p class="lead">Puis la partager avec vos amis.</p>
            </div>
        <div class="explications bg-light shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
        <p class="lead">Explications</p></div>
        </div>
      <div class="articles bg-light text-center overflow-hidden">
        <div>
            <h2 class="display-5">Vous pouvez partager votre liste facilement</h2>
            <p class="lead">Même à ceux qui n'ont pas de compte sur whishList.</p>
            </div>
        <div class="explications2 bg-dark shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
        <p class="lead">Explications</p>
        </div>
    </div>
</div>
        
END;
        
        return $html;
    }
    
    public function monCompte(){
        $lienAccueil = $this->app->urlFor('accueil');
        $lienSupp = $this->app->urlFor('suppCompte');
        
        #Permet la modification dynamique
        try{ session_start();}
        catch(\Exception $e){}
        
        $html = <<<END
        <div class="container">
        <div class="row justify-content-md-center">
                <form method="post" action="">
                        <div  class="col col-lg-4"> 
                                <button type="submit" class="btn btn-danger" name="deconnexion">Se déconnecter</button>
                        </div>
                    </form>
            </div>
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
                            $this->erreur
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
                </div>
END;
        
        $html = $html . $fin;
        
        return $html;
        
    }
    
    public function mesListes(){
        $html = <<<END
        <div class="container">
            <h1>Les listes que vous avez créé ou sur lesquelles vous avez des droits de modification:</h1>
END;
        
        $i=0;
        if(isset($this->liste)){
            foreach($this->liste as $separation){
                foreach($separation as $val){
                    $lien = $this->app->urlFor('listeCrea', array('token' => $val->token));
                    $i++;
                    $modifierListe = $this->app->urlFor('modifierListe', array('token' => $val->token));
                    $supprimerListe = $this->app->urlFor('supprimer_liste', array('token' => $val->token));
                    $html .= '<div class="row">';
                    $html .= '<div class="col-sm-8">';
                    $html .= '<h2><b>'.$i.' : </b><a href =' . $lien . '  >'.$val->titre.'</a></h2><p>' . $val->expiration . '</p>';
                    $html .= '</div>';
                    $html .= '<div class ="col-sm-4">';
                    $html .= '<div class="row">';
                    $html .= '<form method="GET" action= "'.$modifierListe.'">';
                    $html .= '<button class="btn modif col col-lg-6" value="modifierListe">Modifier liste</button>';
                    $html .= "</form>";
                    $html .= '</div>';
                    $html .= '<div class="row">';
                    $html .= '<form method="GET" action= "'.$supprimerListe.'" >';
                    $html .= '<button class="btn del col col-lg-6" value="supprimerListe">Supprimer liste</button>';
                    $html .= "</form>";
                    $html .= "</div>";
                    $html .= "</div>";
                    $html .= "</div>";
                } 
            }
        }
        
        
        if($i == 0){
            $html .= "<h3> vous n'avez pas encore créé de listes!</h3>";  
        }
        
		$creerListe = $this->app->urlFor('creerListe');
        $html .= <<<END
        <div class="row separation " >
            <div class="col-sm-8">
                <h3>Vous voulez créer une liste?</h3>
            </div>
            <div class="col-sm-4">
              <form method="GET" action= "$creerListe">
                <button class="btn add" value="creerListe">Créer listes</button>
              </form>
            </div>
        </div>
        <h1>Vos listes favorites:</h1>
        <p>Ici vous pouvez retrouver rapidement une liste que vous vouliez garder sous la main en l'ajoutant grâce à son token.</p>
END;
        
        $i = 0;
        if(isset($this->listePart)){
            foreach($this->listePart as $val){
                $i++;

                $html .=  "<div class =\"col-lg-8\"><h2><b>$i : </b><a href = $lien  >$val->titre</a><h2>" . "</div><div class =\"col-lg-2\"><form method=\"post\"><button type=\"submit\" class=\"btn del\" name=\"suppression\" value=$val->token>Supprimer</button></form></div>"; 
            }
        }
        
        
        if($i == 0){
            $html .= "<h4> Vous n'avez pas encore ajouté de listes de vos amis!</h4>";  
        }
        
        $html .= <<<END
        <div class="row justify-content-md-center">
        <div class="col-sm-8">
        <p>Ajouter la liste d'un de vos amis? Remplissez le token de sa liste dans le champ prévu et cliquez sur ok</p>
         </div>
         <div class="col-sm-8">
            <form method="post" class="text-center">
                <input type="text" name="token" class="form-control" placeholder="Token liste">
                <button type="submit" class="btn add" name="ajout" value="add">Ajouter</button>
            </form>
            <p class="">$this->erreur</p>
            </div>
        </div>
        </div>
        </div>
        </div>
        </div>
END;
        
        return $html;
    }
    
    public function visionComptes(){
        $perso = $this->membre;
        $amis = $this->amis;
        $liste = $this->liste;
        
        $html = <<<END
        <div class="container justify-content-md-center">
                <h1>$perso->pseudo : #$perso->idUser</h1>
                <img src="../src/img/profil.png" width="150" height="150">
                <p>Message d'humeur : $perso->message</p>
END;
        
        $btn = "";
        
        if(!isset($amis)){
            $btn = "<form method=\"POST\">
                <button name=\"add\" value=\"y\"class=\"btn btn-primary\">Ajouter à sa liste d'ami</button>
            </form>";
        }else{
            if($amis->statut == "Attente"){
                $btn = "<h3>En attente de validation</h3>";
            }
            else{
                $btn = "<h3>Vous etes amis</h3>";
            }
        }
        
        $html .= $btn;
        $listes = '<h2 class="col-lg-5" id="titre">Ses listes:</h2>';
        
        if(count($liste) == 0){
            $listes .= "<p>Cet utilisateur n'a pas crée de listes</p>";
        }
        else{
            $listes.='<div class="row">';
            $i=0;
            foreach($liste as $val){
                $lien = $this->app->urlFor('demandeAcces',['token'=>$val->token]);
                $i++;
                $listes .= "<div class=\"col-lg-6 \">
                <h2><b>$i : </b><a href = $lien>$val->titre</a></h2>
                </div>";       
            }
             $listes.='</div>';
        }
        
        $html .= $listes;
        $html .= "</div>";
        
        return $html;
    }
    
    public function createurs(){
        
        $html = "<div class=\"container\"><div class=\"row\">";
        $app = \Slim\Slim::getInstance();
        foreach($this->membre as $m){
            $pers = $m[0];
            $lien = $app->urlFor('user', array('id' => $pers->idUser));
            $vous="";
            if(Auth::isLogged() && $pers->idUser == $_SESSION['idUser']){
                $vous=' (Vous)';
            }
            $html .= <<<END
            <div class="col-sm-6 col-md-4">
                <a href=$lien><h2>$pers->pseudo # $pers->idUser $vous</h2></a>
                <p>$pers->message</p><p>Il a créé : $m[1] liste(s)
            </div>
END;
        }
        
        $html .="</div></div>";
        
        return $html;
    }
    
    public function contact(){
        $att = $this->demande;
        $amis = $this->amis;
        
        $html = "<div class=\"container\"><h1>Demandes d'amis:</h1>";
        
        $i = 1;
        foreach($att as $val){
            $lien = $this->app->urlFor('user', array('id' => $val->idUser));
            $html .= <<<END
            <div class="row">
            <a class="col-md-8" href=$lien><h3>$i ) $val->pseudo # $val->idUser</h3> </a>
                <form class="col-md-4" method="POST">
                    <button name="ok" class ="btn btn-primary" value="$val->idUser">Accepter</button>
                    <button name="del" class ="btn btn-warning"value="$val->idUser">Supprimer</button>
                </form>
            </div>
END;
        $i++;
        }
        
        if(count($att)==0){
            $html.="<h3>Vous n'avez aucune demande en attente</h3>";
        }
        
        $html .= "<h1>Mes amis</h1>";
        
        $i = 1;
        
        foreach($amis as $val){
            $lien = $this->app->urlFor('user', array('id' => $val->idUser));
            $html .= <<<END
            <div class="row">
            <a class="col-md-8" href=$lien><h3>$i ) $val->pseudo # $val->idUser</h3></a>
                <form class="col-md-4" method="Post">
                    <button name="delUs" class ="btn btn-warning" value="$val->idUser">Supprimer</button>
                </form> 
            </div>
END;
            $i++;
        }
        
        if(count($amis)==0){
            $html.="<h3>Vous n'avez pas encore d'amis, n'hésitez pas à faire une demande à un utilisateur</h3>";
        }
        
        $html.="</div>";
        
        return $html;
    }
    
    private function affichageListes() {
        $html = '<div class ="container"><div class="row justify-content-md-center">';
        $html .= '<form class="row" method="GET" action="' . $this->app->urlFor('listePublic') . '">';
        $html .= '<button name="trie" value="auteur" class="btn btn-primary col-lg-6">Trier par Auteur</button>';
        $html .= '<button name="trie" value="date" class="btn btn-primary col-lg-6">Trier par Date</button>';
        $html .= "</form></div><div class=\"row\">";
        foreach($this->liste as $l){
                $html .= '<div class="col-lg-6 sepa"><p class="titre"><h3>' . $l->titre . '</h3><p class="date">' . $l->expiration . '</p>';
                $html .= '<form method="GET" action="' . $this->app->urlFor('demandeAcces', array('token' => $l->token)) . '">';
                $html .= '<button class="btn btn-warning col-lg-6">Détails</button>';
                $html .= "</form>";
                $html .= '</div>';
        }

        $html .= '</div></div>';

        return $html;
    }
    
    private function affichageListesCrea() {
        $html = '<section><ul class="listes">';

        foreach($this->liste as $l){

            if(isset($l)){
                $html .= '<li><p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><p class="date">' . $l->expiration . '</p>';
                $html .= '<form method="GET" action="' . $this->app->urlFor('listeCrea', array('token' => $l->token)) . '">';
                $html .= '<button class="btn btn-primary">Détails</button>';
                $html .= '<p>Nombre de réservations : ' . count($l->reservation()->get()) . '</p>';
                $html .= "</form>";
                $html .= '</li>';
            }
        }

        $html .= '<form method="GET" action="#">'; // TODO mettre route **TRISTAN**
        $html .= '<button class="btn btn-primary">Ajouter une liste</button>';
        $html .= '</form>';

        $html .= '</ul></section>';

        return $html;
    }
    
    private function affichageListeCrea() {
        $html = '<div class="container">';
        $cpt = 1;


        $l = $this->liste;

        if(isset($l)){
            $items = $l->items()->get();
            $html .='<div class="row centrer">';
            if(!isset($l->message) or empty($l->message))  {
                $html .= '<h1 class="col-12">' . $l->titre . '</h1><p class="col-12">' . $l->description . '</p>';
            } else {
                $html .= '<h1  class="col-12">' . $l->titre . '</h1><p class="col-12"> Description : ' . $l->description . '</p>';
                $html .= '<p class="col-12"><i>Message du créateur :</i> ' . $l->message . '</p>';
            }
            $html.='</div><div class="row">';
            foreach($items as $i){
                $reserv = $i->reservation()->first();

                if(isset($reserv) and ($i->cagnotte == 0)){
                    #reserve ici
                    $html .= '<div class="article col-sm-6 col-md-4"><div class="contenu">';
                } else{
                    $html .= '<div class="article col-sm-6 col-md-4"><div class="contenu">';
                }
                
                if(substr($i->img, 0, 4) == 'http') {
                   $image_item = '<div class="contImage"><img class="imgDesc" src="' . $i->img . '"></div>'; 
                } else {
                   $image_item = '<div class="contImage"><img class="imgDesc" src="' . '../src/img/' . $i->img . '"></div>';
                }
                
                $html .= '<h4>' . $i->nom . '</h4>' . $image_item . '<p class="tarif">' . $i->tarif .  ' €</p>';

                $html .= '<button class="details btn btn-primary h' . $cpt . '">Description</button>';
                if(isset($reserv)){

                    $html .= '<p>Cet item a été réservé !</p>';
                    $html .= '<button class="message btn btn-primary h' . $cpt . '">Voir le message</button>';
                } elseif($i->cagnotte == 0){
                    $html .= '<div class="row"><form method="GET" class="col-md-6" action= "' . $this->app->urlFor('modifierItem', array('id' => $i->id,'token' => $l->token)) . '">';
                    $html .= '<button class="btn modif">Modifier</button>';
                    $html .= '</form>';

                    $html .= '<form class="col-md-6" method="GET" action= "' . $this->app->urlFor('supprimer', array('id' => $i->id,'token' => $l->token)) . '">';
                    $html .= '<button class="btn del">Supprimer</button>';
                    $html .= '</form></div>';
                    
                    $html .= '<form method="POST" action= "' . $this->app->urlFor('creerCagnotte', array('id' => $i->id)) . '">';
                    $html .= '<button class="btn add">Créer une cagnotte</button>';
                    $html .= '</form>';
                } else{
                    $html .= '<div class="row"><form method="GET" class="col-md-6" action= "' . $this->app->urlFor('modifierItem', array('id' => $i->id,'token' => $l->token)) . '">';
                    $html .= '<button class="btn modif">Modifier</button>';
                    $html .= '</form>';

                    $html .= '<form method="GET" class="col-md-6" action ="' . $this->app->urlFor('supprimer', array('id' => $i->id,'token' => $l->token)) . '">';
                    $html .= '<button class="btn del">Supprimer</button>';
                    $html .= '</form></div>';
                }


                $html .= '<section class="details hidden hide' . $cpt . '"><h6>Description :</h6>';
                $html .= '<p class="desc">' . $i->descr . '</p>';

                if($i->url != null or $i->url != ""){
                    $html .= '<a target="_blank" href="' . $i->url . '">Produit disponible ici !</a>';
                } else{
                    $html .= '<p>Aucune URL associée !</p>';
                }

                $html .= '</section>';


                if(isset($reserv)){
                    $html .= '<section class="message hidden hide' . $cpt . '">';

                    $html .= '<h6>Messages :</h6>';
                    $html .= '<p class="message">' . $reserv->message . '</p>';

                }

                $html .= '</div></div>';


                $cpt++;
            }

            $html .= '</div>';
            $html .= '<p class="date">Date d\'échéance :</p><p class="date">' . $l->expiration . '</p>';

        }

        $html .= '<div class="row"><form method="GET" class="col-md-6" action= "' . $this->app->urlFor('ajouterItem', array('token' => $l->token)) . '">';
        $html .= '<button class="btn add">Ajouter un item</button>';
        $html .= '</form>';

        $html .= '<p class="col-md-6"><button class="partager btn btn-primary">Partager</button></p></div>';
        
        
        $html .= '<form method="POST" class="row bottom" action="' . $this->app->urlFor('ajoutMsgListe', array('token' => $l->token)) . '">';
        $html .= '<textarea id="msg" class="col-md-8 form-control" rows="2" type="text" name="message_liste" placeholder="Message"></textarea>';
        $html .= '<button type="submit" class="col-md-4 btn add" name="ajouter_message_liste">Ajouter un message</button>';
        $html .= '</form>';
        
        if(isset($l)){
            $html .= '<div class="partager hidden hide modal">';
            $html .= '<div class="form">';
            $html .= '<h6>Lien de partage :</h6>';
            $html .= '<p>Le lien de partage vous permet de partager votre liste à qui vous souhaitez, même des personnes qui ne sont pas inscrites sur le site.</p>';
            $html .= '<input type="text" name="lien" value="' . $_SERVER['HTTP_HOST'] . $this->app->urlFor('listeShare', array('share' => $l->share)) . '" disabled>';
            $html .= '<button class="fermer btn btn-primary">Fermer</button>';
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
    
    private function affichageListeInvite() {
        $html = '<section class="listes">';
        $cpt = 1;

        $l = $this->liste;

        if(isset($l)){
            $items = $l->items()->get();

            if(!isset($l->message) or empty($l->message))  {
                $html .= '<p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><div class="row items">';
            } else {
                $html .= '<p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p>';
                $html .= '<br><p><i><b>Message du créateur :</b></i> ' . $l->message . '</p><div class="row items">';
            }
            
            foreach($items as $i){

                if(substr($i->img, 0, 4) == 'http') {
                   $image_item = '<img class="imgDesc" src="' . $i->img . '">'; 
                } else {
                   $image_item = '<img class="imgDesc" src="../../src/img/' . $i->img . '">';
                }
                
                if($i->cagnotte == 1){
                    
                    $html .= '<div class="col col-l-3">';

                    $html .= '<p class="nom"><h4>' . $i->nom;

                    $html .= '</h4></p>' . $image_item;

                    $html .= '<p class="tarif">' . $i->tarif .  ' €</p>' . '<br/><br/>';

                    $html .= '<button class="details btn btn-primary h' . $cpt . '">Détails</button>';

                    if(! $i->participationPossible()){

                        $html .= '<button disabled class="btn btn-primary cagnotte h' . $cpt . '">Participer à la cagnotte</button>';

                        $html .= '<div class="cagnotte">';
                        $html .= '<p>Le montant cible de la cagnotte a été atteint !</p>';
                        $html .= '</div>';

                    } else{
                        $html .= '<button class="btn btn-primary cagnotte h' . $cpt . '">Participer à la cagnotte</button>';
                        
                        $html .= '<div class="cagnotte modal h' . $cpt . '"><div class="form">';
                        $html .= '<form id="Cagn" method="POST" action="' . $this->app->urlFor('participerCagnotte', array('id' => $i->id)) . '">';
                        $html .= '<p>Nom de l\'item auquel vous participer : </p><input type="text" name="nomItem" value="' . $i->nom . '" disabled>';
                        $html .= '<p>Montant de participation : </p><input type="text" name="montant" value="" required>';

                        $n = ''; $p = '';
                        $idUser = Auth::getIdUser();

                        if(isset($idUser)){
                            $m = Membre::where('idUser', '=', $idUser)->first();
                            $n = $m->nom;
                            $p = $m->prenom;
                        }


                        $html .= '<p>Votre nom : </p><input type="text" name="nom" value="' . $n . '" required>';
                        $html .= '<p>Votre prénom : </p><input type="text" name="prenom"
                        value="' . $p . '" required>';
                        $html .= '<p>Message : </p><textarea rows="5" cols="50" type="text" name="message" value="" form="Cagn"></textarea>';

                        $html .= '<button type="submit" class="btn btn-primary confirmerC h' . $cpt . '">Participer</button>';

                        $html .= '</form>';
                        $html .= '<button class="btn btn-primary annulerC h' . $cpt . '">Annuler</button>';

                        $html .= '</div>';
                        $html .= '</div>';
                    }
                    
                    $html .= '<section class="details hidden hide' . $cpt . '"><h6>Description :</h6>';
                    $html .= '<p class="desc">' . $i->descr . '</p>';

                    if($i->url != null and $i->url != ""){
                        $html .= '<a target="_blank" href="' . $i->url . '">Produit disponible ici !</a>';
                    } else{
                        $html .= '<p>Aucune URL associée !</p>';
                    }
                    
                    $html .= '</section>';


                    $html .= '</div>';
                    
                } else{
                    $reserv = $i->reservation()->first();
                    
                    if(isset($reserv)){
                        $html .= '<div class="reserve col col-l-3">';
                    } else{
                        $html .= '<div class="col col-l-3">';
                    }

                    $html .= '<p class="nom"><h4>' . $i->nom;

                    $html .= '</h4></p>' . $image_item;

                    $html .= '<p class="tarif">' . $i->tarif .  ' €</p>' . '<br/><br/>';

                    $html .= '<button class="details btn btn-primary h' . $cpt . '">Détails</button>';

                    
                    if(isset($reserv)){

                        $html .= '<button disabled class="btn btn-primary reserver h' . $cpt . '">Réserver</button>';

                        $html .= '<div class="reserv">';
                        $html .= '<p>Cet item a déjà été réservé par :</p>';
                        $html .= '<p>' . $reserv->prenom . ' ' . $reserv->nom . '</p>';
                        $html .= '</div>';

                    } else{

                        $html .= '<button class="btn btn-primary reserver h' . $cpt . '">Réserver</button>';

                        $html .= '<div class="reserver modal h' . $cpt . '"><div class="form">';
                        $html .= '<form id="Reserv" method="POST" action="' . $this->app->urlFor('reserver', array('share' => $l->share, 'idItem' => $i->id)) . '">';
                        $html .= '<p>Nom de l\'item à reserver : </p><input type="text" name="nomItem" value="' . $i->nom . '" disabled>';
                        $html .= '<p>Prix de réservation : </p><input type="text" name="prix" value="' . $i->tarif . '" disabled required>';

                        $n = ''; $p = '';
                        $idUser = Auth::getIdUser();

                        if(isset($idUser)){
                            $m = Membre::where('idUser', '=', $idUser)->first();
                            $n = $m->nom;
                            $p = $m->prenom;
                        }

                        $html .= '<p>Votre nom : </p><input type="text" name="nom" value="' . $n . '" required>';
                        $html .= '<p>Votre prénom : </p><input type="text" name="prenom" value="' . $p . '">';
                        $html .= '<p>Message : </p><textarea rows="5" cols="50" type="text" name="message" value="" form="Reserv"></textarea>';

                        $html .= '<button type="submit" class="btn btn-primary confirmerR h' . $cpt . '">Réserver</button>';

                        $html .= '</form>';
                        $html .= '<button class="btn btn-primary annulerR h' . $cpt . '">Annuler</button>';

                        $html .= '</div>';
                        $html .= '</div>';

                    }

                    $html .= '<section class="details hidden hide' . $cpt . '"><h6>Description :</h6>';
                    $html .= '<p class="desc">' . $i->descr . '</p>';

                    if($i->url != null and $i->url != ""){
                        $html .= '<a target="_blank" href="' . $i->url . '">Produit disponible ici !</a>';
                    } else{
                        $html .= '<p>Aucune URL associée !</p>';
                    }
                    $html .= '</section>';


                    $html .= '</div>';
                }
                
                $cpt++;
                
            }

            $html .= '</div>';
            $html .= '<p class="date">Date d\'échéance :</p><p class="date">' . $l->expiration . '</p>';
        }

        $html .= '</section>';

        return $html;
    }
    
    private function recherche(){
        $html = '<div class="recherche row justify-content-md-center">';
        $html .= '<form class="form-inline my-2 my-md-0" id="search" method="GET" action="' . $this->app->urlFor('rechercheAvancee') . '">';
        $html .= '<div class="row col-md-12 justify-content-md-center">
        <input class="form-control" type="text" name="search" placeholder="Terme recherché" value="' . $this->recherche['search'] . '">
        </div>';
        
        if(isset($this->recherche['on'])){
            $html .= '<div class="col col-md-3">
            <p>Option de filtre :</p>';
            
            if($this->recherche['on'] == 'Listes'){
                $html .= '<div class="grpRech">
                <input type="radio" name="on" value="Listes" checked>
                <label for="search">Listes</label></div>';
            } else{
                $html .= '<div class="grpRech">
                <input type="radio" name="on" value="Listes">
                <label for="search">Listes</label></div>';
            }
            
            if($this->recherche['on'] == 'Créateurs'){
                $html .= '<div class="grpRech">
                <input type="radio" name="on" value="Créateurs" checked>
                <label for="search">Créateurs</label></div>';
            } else{
                $html .= '<div class="grpRech">
                <input type="radio" name="on" value="Créateurs">
                <label for="search">Créateurs</label></div>';
            }
            
            if($this->recherche['on'] == 'Membres'){
                $html .= '<div class="grpRech">
                <input type="radio" name="on" value="Membres" checked>
                <label for="search">Membres</label></div>';
            } else{
                $html .= '<div class="grpRech">
                <input type="radio" name="on" value="Membres">
                <label for="search">Membres</label></div>';
            }
            
            if($this->recherche['on'] == 'Les deux'){
                $html .= '<div class="grpRech">
                <input type="radio" name="on" value="Les deux" checked>
                <label for="search">Les deux</label></div>';
            } else{
                $html .= '<div class="grpRech">
                <input type="radio" name="on" value="Les deux">
                <label for="search">Les deux</label></div>';
            }
            
            $html .= '</div>';
            
        } else{
            $html .= '<div class="col col-md-3">
            <p>Option de filtre :</p>
            <div class="grpRech"><input type="radio" name="on" value="Listes">
            <label for="search">Listes</label></div>
            <div class="grpRech"><input type="radio" name="on" value="Créateurs">
            <label for="search">Créateurs</label></div>
            <div class="grpRech"><input type="radio" name="on" value="Membres">
            <label for="search">Membres</label></div>
            <div class="grpRech"><input type="radio" name="on" value="Les deux" checked>
            <label for="search">Les deux</label></div>
            </div>'; 
        }
        
        if(isset($this->recherche['date'])){
            $html .= '<div class="col col-md-2">
            <p>Filtre par date d\'échéance :</p>
            <input type="date" name="date" value="' . $this->recherche['date'] . '">
            </div>';
        } else{
            $html .= '<div class="col col-md-2">
            <p>Filtre par date d\'échéance :</p>
            <input type="date" name="date">
            </div>';
        }
        
        
        if(isset($this->recherche['deep']) and ($this->recherche['deep'] == 'deep')){
            $html .= '<div class="col col-md-3">
            <input type="checkbox" name="deep" value="deep" checked><label for="search" checked>Recherche profonde</label>
            <p><i>Recherche le mot clé dans la description des listes et/ou infos utilisateurs.</i></p>
            </div>';
        } else{
            $html .= '<div class="col col-md-3">
            <input type="checkbox" name="deep" value="deep"><label for="search">Recherche profonde</label>
            <p><i>Recherche le mot clé dans la description des listes et/ou infos utilisateurs.</i></p>
            </div>';
        }
        
        if(isset($this->recherche['nbReserv'])){
            $html .= '<div class="col col-md-2">
            <p>Filtre par nombre de réservations :</p>
            <input type="number" name="nbReserv" value="' . $this->recherche['nbReserv'] . '">';
            
            if($this->recherche['reserv'] == 'Minimum'){
                $html .= '<div class="grpRech">
                <input type="radio" name="reserv" value="Minimum" checked>
                <label for="search">Au minimum</label></div>
                <div class="grpRech">
                <input type="radio" name="reserv" value="Maximum">
                <label for="search">Au maximum</label></div>
                <div class="grpRech">
                <input type="radio" name="reserv" value="Exact">
                <label for="search">Exactement</label></div>';
                
            } elseif($this->recherche['reserv'] == 'Maximum'){
                $html .= '<div class="grpRech">
                <input type="radio" name="reserv" value="Minimum">
                <label for="search">Au minimum</label></div>
                <div class="grpRech">
                <input type="radio" name="reserv" value="Maximum" checked>
                <label for="search">Au maximum</label></div>
                <div class="grpRech">
                <input type="radio" name="reserv" value="Exact">
                <label for="search">Exactement</label></div>';
                
            } else{
                $html .= '<div class="grpRech">
                <input type="radio" name="reserv" value="Minimum">
                <label for="search">Au minimum</label></div>
                <div class="grpRech">
                <input type="radio" name="reserv" value="Maximum">
                <label for="search">Au maximum</label></div>
                <div class="grpRech">
                <input type="radio" name="reserv" value="Exact" checked>
                <label for="search">Exactement</label></div>';
                
            }
            
            $html .= '</div>';
            
        } else{
            $html .= '<div class="col col-md-2">
            <p>Filtre par nombre de réservations :</p>
            <input type="number" name="nbReserv">
            <div class="grpRech">
            <input type="radio" name="reserv" value="Minimum">
            <label for="search">Au minimum</label></div>
            <div class="grpRech">
            <input type="radio" name="reserv" value="Maximum">
            <label for="search">Au maximum</label></div>
            <div class="grpRech">
            <input type="radio" name="reserv" value="Exact" checked>
            <label for="search">Exactement</label></div>
            </div>';
        }
        
        
        if(isset($this->recherche['nbItem'])){
            $html .= '<div class="col col-md-2">
            <p>Filtre par nombre d\'items :</p>
            <input type="number" name="nbItem" value="' . $this->recherche['nbItem'] . '">';
            
            if($this->recherche['item'] == 'Minimum'){
                $html .= '<div class="grpRech">
                <input type="radio" name="item" value="Minimum" checked>
                <label for="search">Au minimum</label></div>
                <div class="grpRech">
                <input type="radio" name="item" value="Maximum">
                <label for="search">Au maximum</label></div>
                <div class="grpRech">
                <input type="radio" name="item" value="Exact">
                <label for="search">Exactement</label></div>';
                
            } elseif($this->recherche['item'] == 'Maximum'){
                $html .= '<div class="grpRech">
                <input type="radio" name="item" value="Minimum">
                <label for="search">Au minimum</label></div>
                <div class="grpRech">
                <input type="radio" name="item" value="Maximum" checked>
                <label for="search">Au maximum</label></div>
                <div class="grpRech">
                <input type="radio" name="item" value="Exact">
                <label for="search">Exactement</label></div>';
                
            } else{
                $html .= '<div class="grpRech">
                <input type="radio" name="item" value="Minimum">
                <label for="search">Au minimum</label></div>
                <div class="grpRech">
                <input type="radio" name="item" value="Maximum">
                <label for="search">Au maximum</label></div>
                <div class="grpRech">
                <input type="radio" name="item" value="Exact" checked>
                <label for="search">Exactement</label></div>';
                
            }
            
            $html .= '</div>';
            
        } else{
            $html .= '<div class="col col-md-2">
            <p>Filtre par nombre d\'items :</p>
            <input type="number" name="nbItem">
            <div class="grpRech">
            <input type="radio" name="item" value="Minimum">
            <label for="search">Au minimum</label></div>
            <div class="grpRech">
            <input type="radio" name="item" value="Maximum">
            <label for="search">Au maximum</label></div>
            <div class="grpRech">
            <input type="radio" name="item" value="Exact" checked>
            <label for="search">Exactement</label></div>
            </div>';
        }
        
        $html .= '<div class="row col-md-12 justify-content-md-center">
        <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>';
        
        $html .= '</form></div>';
        $html .= '<section>';
        
        
        if(isset($this->liste) and (count($this->liste) > 0)){
            $html .= '<div><h3>Listes :</h3>';
            foreach($this->liste as $l){
                if(isset($l->share) and ($l->share != '')){
                    $html .= '<p><a class="nav-link" href="' . $this->app->urlFor('listeShare', array('share' => $l->share)) . '">' . $l->titre . '</a></p>';
                }
            }
            $html .= '</div>';
        }
        
        if(isset($this->membre) and (count($this->membre) > 0)){
            $html .= '<div><h3>Membre / Créateur :</h3>';
            foreach($this->membre as $m){
                if(isset($m->idUser) and ($m->idUser != '')){
                    $html .= '<p><a class="nav-link" href="' . $this->app->urlFor('user', array('id' => $m->idUser)) . '">' . $m->nom . ' ' . $m->prenom . '</a></p>';
                }
            }
            $html .= '</div>';
        }
        
        $html .= '</div></section>';
        
        return $html;
    }
    
    private function ajouterItem() {
        $html = '<section>';
        $ajouter_item = $this->app->urlFor('ajouter_item');

        //    $html .= '<li><p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><p class="date">' . $l->expiration . '</p>';
        //  $html .= '<form method="GET" action="' . $this->app->urlFor('ajouterItem', array('token' => $l->token)) . '">';
        //  $html .= '<button class="btn btn-primary">Détails</button>';
        //  $html .= "</form>";
        $html .= '<div class="row"><div class="col-md-2">';
        $html .= '<form method="post" action="$ajouter_item" enctype="multipart/form-data">';
        $html .= '<p><input type="text" name="nom" class="form-control" aria-describedby="emailHelp" placeholder="Nom" required autofocus/></p>';
        $html .= '<p><textarea type="text" rows="5" cols="50" name="description" class="form-control" aria-describedby="emailHelp" placeholder="Description" required/></textarea></p>';
        $html .= '<p><input type="number" name="tarif" class="form-control" aria-describedby="emailHelp" placeholder="Tarif" required/></p>';
        $html .= '<p><input type="url" name="url" class="form-control" aria-describedby="emailHelp" placeholder="lien utile"/></p>';
        $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />';
        $html .= '<p><input type="file" name="image" id="image" accept=".png, .jpg, .jpeg" /></p>';
        $html .= '<p><input type="text" name="image_url" class="form-control" placeholder="URL de l\'image"/></p>';
        $html .= '<p><button type="submit" class="btn btn-primary" name="valider" value="ajouter_item">Valider</button></p>';
        $html .= '</form>';
        $html .= '</div></div>';

        $html .= '</section>';

        return $html;
    }

    private function modifierItem() {
        $html = '<div class="container">';
        $i = $this->item;
        $modifier_item = $this->app->urlFor('modifier_item');
        $id=$i->id;

        $html .= '<h1>Modification de l\'item</h1>';
        $html .= '<form method="POST" action="'.$id.'" enctype="multipart/form-data" class="row">';
        $html .= '<div class="col-lg-6"><h3>Nom : </h3><input type="text" name="nom" class="form-control" aria-describedby="emailHelp" placeholder="Nom" value="'.$i->nom.'" autofocus/></div>';
        $html .= '<div class="col-lg-6"><h3>Description : </h3><textarea rows="1" cols="50" class="form-control" type="text" name="description" value="">'.$i->descr.'</textarea></p></div>';
        //$html .= '<p><input type="text" name="description" class="form-control" aria-describedby="emailHelp" placeholder="Description" value="'.$i->descr.'" /></p>';
        $html .= '<div class="col-lg-6"><h3>Tarif : </h3><input type="number" name="tarif" class="form-control" aria-describedby="emailHelp" placeholder="Tarif" value="'.$i->tarif.'" /></p></div>';
        $html .= '<div class="col-lg-6"><h3>Lien utiles : </h3><input type="url" name="url" class="form-control" aria-describedby="emailHelp" placeholder="lien utile" value="'.$i->url.'" /></div>';
        $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>';
        $html .= '<div class="col-lg-12"><h3>Photo</h3><div class="row"><input class="col-lg-6" type="file" name="image" id="image" accept=".png, .jpg, .jpeg" /></p>';
        $html .= '<input type="text" name="image_url" class="form-control col-lg-6" placeholder="URL de l\'image"/></div></div>';
        $html .= '<div class="col-12"><button type="submit" class="btn btn-danger" name="supprimer_img" value="supprimer_image">Supprimer l\'image</button>';
        $html .= '<button type="submit" class="btn btn-success" name="valider_modif" value="modifier_itesm">Valider modification</button></div>';
        $html .= '</form>';


        $html .= '</div>';

        return $html;
    }

    private function creerListe() {
        $html = '<section>';
        $creer_liste = $this->app->urlFor('creer_liste');

          $html .= '<div class="row"><div class="col-md-8">';
	      $html .= '<h1>Vous pouvez créer une liste ici</h1>';
		  $html .= '<h3>Veuillez saisir les informations de la liste :</h3>';
          $html .= '<div class="row"><div class="col-md-5">';
		  $html .= '<form method="POST" action="">';
		  $html .= '<p><input type="text" name="titre" class="form-control" aria-describedby="emailHelp" placeholder="Titre" value="" required autofocus/></p>';
		  $html .= '<p><input type="text" name="descr" class="form-control" aria-describedby="emailHelp" placeholder="Description" value="" required/></p>';
		  $html .= '<p><input type="date" name="date" class="form-control" aria-describedby="emailHelp" placeholder="Date d\'expiration" value="" required/></p>';
	   	  $html .= '<div class="col-md-6">';
		  $html .= '<p><input type="radio" name="liste_publique" value="1"> Liste publique</p>';
		  $html .= '<p><input type="radio" name="liste_publique" value="0"> Liste privée</p>';
		  $html .= '</div>';
		  $html .= '<p><button type="submit" class="btn btn-primary" name="creerUneListe" value="creer_Liste">Créer la liste</button></p>';
		  $html .= '</form>';
		  $html .= '</div></div></div>';
		  $html .= '</section>';

        return $html;
    }
    
    private function modifierListe() {
        $html = '<div class="container justify-content-md-center">';
        $creer_liste = $this->app->urlFor('modifier_liste');
        $li = $this->liste;

        $html .= '<div class="row "><div class="col-12">';
        $html .= '<h1>Vous pouvez modifier les information de la liste ici</h1>';
        $html .= '<div class="row justify-content-md-center">';
        $html .= '<form class="col-12" method="POST" action="">';
        $html .= '<div class ="col-lg-6"><h3>Titre:</h3><input type="text" name="titre" class="form-control col-lg-6" aria-describedby="emailHelp" placeholder="Titre" value="'.$li->titre.'" autofocus/></div>';
        $html .= '<div class ="col-lg-6"><h3>Description:</h3><input type="text" name="descr" class="form-control col-lg-6" aria-describedby="emailHelp" placeholder="Description" value="'.$li->description.'" /></div>';
        $html .= '<div class ="col-lg-6"><h3>Date expiration:</h3><input type="date" name="date" class="form-control col-lg-6" aria-describedby="emailHelp" placeholder="Date d\'expiration" value="'.$li->expiration.'" /></div>';
        $html .= '<div class="row col-lg-6">';
        if($li->public == 0){
          $html .= '<p class="col-lg-6"><input type="radio" name="liste_publique" value="1"> Liste publique</p>';
          $html .= '<p class="col-lg-6"><input type="radio" name="liste_publique" value="0" checked> Liste privée</p>';
        }else{
          $html .= '<p class="col-lg-6"><input type="radio" name="liste_publique" value="1" checked> Liste publique</p>';
          $html .= '<p class="col-lg-6"><input type="radio" name="liste_publique" value="0"> Liste privée</p>';
        }
        $html .= '</div>';
        $html .= '<p><button type="submit" class="btn btn-primary" name="valider_modif" value="modifier_liste">Valider modification</button></p>';
        $html .= '</form>';
        $html .= '</div></div></div></div>';

        return $html;
    }
    
    public function render($code){
        
        $contenu = "<h1>ERREUR !</h1>";
        $style = "";
        $path = "";
        
        switch($code){
            case 'ACCUEIL':{
                $contenu = $this->accueil();
                break;
            }
            case 'COMPTE':{
                if($this->messageErreur!=""){
                    $this->messageErreur="<p class=\"erreur\">$this->messageErreur</p>";
                }
                $contenu = $this->monCompte();
                $style = "<link rel=\"stylesheet\" href=\"./src/css/monCompte.css\">"; 
                break;
            }
            case 'SUPPCOMPTE':{
                $contenu = $this->supprimerCompte();
                break;    
            }
            case 'CONFSUPP':{
                $contenu = $this->confSupp();
                break;
            }
            case 'MESLISTES':{
                $contenu = $this->mesListes();
                $style="<link rel=\"stylesheet\" href=\"./src/css/mesListes.css\">";
                break;
            }
            case 'CREATEURS':{
                $contenu = $this->createurs();
                break;
            }
            case 'VISIONCOMPTES':{
                $contenu = $this->visionComptes();
                $path=".";
                 $style = '<link rel="stylesheet"  href="' . $path . './src/css/visionCompte.css"/>';
                break;
            }
            case 'CONTACT':{
                $contenu = $this->contact();
                 $style = '<link rel="stylesheet"  href="' . $path . 'src/css/contact.css"/>';
                break;
            }
            case 'LISTES_CREA':{
                $contenu = $this->affichageListesCrea();
                
                break;
            }
            case 'LISTES':{
                $contenu = $this->affichageListes();
				$path = '';
                $style = '<link rel="stylesheet"  href="' . $path . './src/css/itemsListes.css"/>';
                $style.='<link rel="stylesheet" href="'.$path.'./src/css/listePub.css"/>';
                break;
            }
            case 'LISTE_CREA':{
                $contenu = $this->affichageListeCrea();
                $path = '.';
                $style = '<link rel="stylesheet"  href="' . $path . './src/css/itemsListes.css"/>';
                break;
            }
            case 'LISTE_CO':{
                $contenu = $this->affichageListeInvite();
                $path = '../.';
                $style = '<link rel="stylesheet"  href="' . $path . './src/css/itemsListes.css"/>';
                break;
            }
            case 'LISTE_INV':{
                $contenu = $this->affichageListeInvite();
                $path = '../.';
                $style = '<link rel="stylesheet"  href="' . $path . './src/css/itemsListes.css"/>';
                break;
            }
            case 'ITEM_AJOUT':{
                $contenu = $this->ajouterItem();
                $path = '../.';
                $style = '<link rel="stylesheet"  href="' . $path . './src/css/itemsListes.css"/>';
                break;
            }
            case 'RECHERCHE':{
                $contenu = $this->recherche();
                $style = '<link rel="stylesheet"  href="' . $path . './src/css/itemsListes.css"/>';
                break;
            }
            case 'MODIFIER':{
                $contenu = $this->modifierItem();
                $style = '<link rel="stylesheet"  href="' . $path . 'src/css/itemsListes.css"/>';
                $path = '../../.';
                break;
            }
            case 'CREER_LISTE':{
                $contenu = $this->creerListe();
                $path = '.';
                $style = '<link rel="stylesheet"  href="' . $path . './src/css/itemsListes.css"/>';
                break;
            }
            case 'MODIFIER_LISTE':{
                $contenu = $this->modifierListe();
                $style = '<link rel="stylesheet"  href="' . $path . 'src/css/itemsListes.css"/>';
                $path = '../.';
                break;
            }
                
        }
        
        $lienAccueil = $this->app->urlFor('accueil');
        $lienCompte = $this->app->urlFor('Compte');
        $lienMesListes = $this->app->urlFor('mesListes');
        $lienListesPublic = $this->app->urlFor('listePublic', array('trie' => 'DATE'));
        $lienCreateur = $this->app->urlFor('createur');
        $lienContact = $this->app->urlFor('contact');
        $lienRecherche = $this->app->urlFor('recherche');
        $title = strtolower($code);
        
        $html = <<< END
        <!DOCTYPE HTML>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../favicon.ico">

    <title>$title</title>
    <link rel="stylesheet" href="$path./src/css/bootstrap.min.css">
    <link rel="stylesheet" href="$path./src/css/principale.css">
    $style
    <script src="$path./src/js/itemsListes.js"></script>
  </head>

  <body>
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
              <a class="navbar-brand" href="$lienAccueil">
              <img src="$path./src/img/logo.png" width="120" height="50" alt="">
              </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
               <form class="form-inline my-2 my-md-0 method="GET" action="$lienRecherche">
                  <input class="form-control" type="text" name="search" placeholder="Rechercher">
                </form> 
              <div class="collapse navbar-collapse" id="navbarsExample04">
                <ul class="navbar-nav mr-auto">
                 <li class="nav-item dropdown active">
                    <a class="nav-link dropdown-toggle" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Listes</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">
                      <a class="dropdown-item" href=$lienMesListes>Mes listes </a>
                      <a class="dropdown-item" href=$lienListesPublic>Les listes du moment</a>
                    </div>
                  </li>
                  <li class="nav-item active">
                    <a class="nav-link" href=$lienCreateur>Listes créateurs<span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item active">
                    <a class="nav-link" href=$lienContact>Contact <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active" id="compte">
                    <a class="nav-link" href=$lienCompte>Mon compte <span class="sr-only">(current)</span></a>
                  </li>
                </ul>
                </div>
                <a class="nav-item " href=$lienCompte>
                    <img src="$path./src/img/profil.png" width="40" height="40" alt="">
                </a>
            </nav>
                <div class="messageErreur $this->typeErreur"><p>$this->messageErreur</p></div>
                
                $contenu
        
        </body>
        <footer class="text-muted text-center text-small">
                <p class="mb-1">&copy; 2018-2019 Site réalisé dans le module de PHP S3</p>
                <ul class="list-inline">
                  <li class="list-inline-item"><a href=https://iut-charlemagne.univ-lorraine.fr/>Iut Nancy Charlemagne</a></li>
                </ul>
        </footer>
               <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="$path./src/js/bootstrap.min.js"></script>
     </html>
END;
        //<div class="messageErreur $this->typeErreur"><p>$this->messageErreur</p></div>
        
        echo $html;
    }
}