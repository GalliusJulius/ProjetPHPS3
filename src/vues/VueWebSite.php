<?php
namespace wishlist\vues;

class VueWebSite{
    
    private $liste, $listePart, $item, $membre, $amis, $demande, $recherche, $erreur, $app;
    
    public function __construct($tab){
        
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
        $lienAccueil = $this->app->urlFor('accueil');
        $lienSupp = $this->app->urlFor('suppCompte');
        
        #Permet la modification dynamique
        try{ session_start();}
        catch(\Exception $e){}
        
        $html = <<<END
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
END;
        
        $html = $html . $fin;
        
        return $html;
        
    }
    
    public function mesListes(){
        $html = <<<END
        <div class="row justify-content-md-center">
            <div class="col col-lg-12 justify-content-md-center">
            <h1>Les listes que vous avez créé:</h1>
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
            }
        }
        
        
        if($i == 0){
            $html .= "<h3> vous n'avez pas encore créé de listes!</h3>";  
        }
        
		$creerListe = $this->app->urlFor('creerListe');
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
        
        $i = 0;
        if(isset($this->listePart)){
            foreach($this->listePart as $val){
                $lien = $this->app->urlFor('listeCrea', array('token' => $val->token));
                $i++;

                $html .=  "<div class =\"col-lg-8\"><h2><b>$i : </b><a href = $lien  >$val->titre</a><h2>" . "</div><div class =\"col-lg-2\"><form method=\"post\"><button type=\"submit\" class=\"btn btn-danger\" name=\"suppression\" value=$val->token>Supprimer</button></form></div>"; 
            }
        }
        
        
        if($i == 0){
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
            <p class="">$this->erreur</p>
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
                <button name=\"add\" value=\"y\"class=\"btn btn-primary\">Ajouter à sa liste d'ami</button>
            </form>";
        }else{
            if($amis->statut == "Attente"){
                $btn = "<h3>En attente de validation</h3>";
            }
            else{
                $btn = "<h3>Vous etes déjà amis</h3>";
            }
        }
        
        $html .= $btn;
        $listes = "";
        
        if(count($liste) == 0){
            $listes = "<p>Cet utilisateur n'a pas crée de listes</p>";
        }
        else{
            $i=0;
            foreach($liste as $val){
                $lien = $this->app->urlFor('listeCrea',['token'=>$val->token]);
                $i++;
                $listes .= "<div class=\"col col-lg-6 \">
                <h2><b>$i : </b><a href = $lien>$val->titre</a><h2>
                </div>";       
            }
        }
        
        $html .= $listes;
        $html .= "</div></div>";
        
        return $html;
    }
    
    public function createurs(){
        $html = "<div class=\"row\">";
        
        foreach($this->membre as $m){
            $html .= "<div class=\"col-lg-6\"><h2>$m->Pseudo</h2><p>Ce créateur n'a pas de messages d'humeurs</p><p>Il a créé : " . count($m->listes()) . " liste(s)</div>";
        }
        
        $html .="</div>";
        
        return $html;
    }
    
    public function contact(){
        $att = $this->demande;
        $amis = $this->amis;
        
        $html = "<h1>Demandes d'amis:</h1>";
        
        foreach($att as $val){
            $html .= "<p>$val->Pseudo</p> 
                <form method=\"POST\">
                    <button name=\"ok\" class =\"btn btn-primary\"value=\"$val->idUser\">Accepter</button>
                    <button name=\"del\" class =\"btn btn-warning\"value=\"$val->idUser\">Supprimer</button>
                </form>";
        }
        
        $html .= "<h1>Mes amis</h1>";
        
        foreach($amis as $val){
            $html .= "<p>$val->Pseudo</p> <form method=\"Post\"><button name=\"delUs\" class =\"btn btn-warning\"value=\"$val->idUser\">Supprimer</button></form>";  
        }
        
        return $html;
    }
    
    private function affichageListes() {
        $html = '<section><ul class="listes">';

        foreach($this->liste as $l){

            if(isset($l)){
                $html .= '<li><p class="titre"><h3>' . $l->titre . '</h3></p><p class="desc">' . $l->description . '</p><p class="date">' . $l->expiration . '</p>';
                $html .= '<form method="GET" action="' . $this->app->urlFor('listeCrea', array('token' => $l->token)) . '">';
                $html .= '<button class="btn btn-primary">Détails</button>';
                $html .= '<p>Nombre de réservations : ' . count($l->reservations()->get()) . '</p>';
                $html .= "</form>";
                $html .= '</li>';
            }
        }

        $html .= '</ul></section>';

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
                   $image_item = '<img src="' . $i->img . '">'; 
                } else {
                   $image_item = '<img src="' . '../src/img/' . $i->img . '">';
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
                            $n = $m->Nom;
                            $p = $m->Prénom;
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
                    
                    $html .= '<section class="details hidden hide' . $cpt . '"><h6 class="hidden">Description :</h6>';
                    $html .= '<p class="hidden desc">' . $i->descr . '</p>';

                    if($i->url != null and $i->url != ""){
                        $html .= '<a class="hidden" target="_blank" href="' . $i->url . '">Produit disponible ici !</a>';
                    } else{
                        $html .= '<p class="hidden">Aucune URL associé !</p>';
                    }
                    
                    $html .= '</section>';


                    $html .= '</div>';
                    
                } else{
                    $reserv = $i->reservation();
                    
                    if(isset($reserv)){
                        $html .= '<div class="reserve col col-l-3">';
                    } else{
                        $html .= '<div class="col col-l-3">';
                    }

                    $html .= '<p class="nom"><h4>' . $i->nom;

                    $html .= '</h4></p><img src="' . $image_item . '">';

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
                            $n = $m->Nom;
                            $p = $m->Prenom;
                        }

                        $html .= '<p>Votre nom : </p><input type="text" name="nom" value="' . $n . '" required>';
                        $html .= '<p>Votre prénom : </p><input type="text" name="prenom" value="' . $p . '" required>';
                        $html .= '<p>Message : </p><textarea rows="5" cols="50" type="text" name="message" value="" form="Reserv"></textarea>';

                        $html .= '<button type="submit" class="btn btn-primary confirmerR h' . $cpt . '">Réserver</button>';

                        $html .= '</form>';
                        $html .= '<button class="btn btn-primary annulerR h' . $cpt . '">Annuler</button>';

                        $html .= '</div>';
                        $html .= '</div>';

                    }

                    $html .= '<section class="details hidden hide' . $cpt . '"><h6 class="hidden">Description :</h6>';
                    $html .= '<p class="hidden desc">' . $i->descr . '</p>';

                    if($i->url != null and $i->url != ""){
                        $html .= '<a class="hidden" target="_blank" href="' . $i->url . '">Produit disponible ici !</a>';
                    } else{
                        $html .= '<p class="hidden">Aucune URL associé !</p>';
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
        $html = '<div class="row justify-content-md-center">';
        $html .= '<form class="form-inline my-2 my-md-0" id="search" method="GET" action="' . $this->app->urlFor('rechercheAvancee') . '">';
        $html .= '<div class="row col-md-12 justify-content-md-center">
        <input class="form-control" type="text" name="search" placeholder="Terme recherché" value="' . $this->recherche['search'] . '">
        </div>';
        
        if(isset($this->recherche['on'])){
            $html .= '<div class="col col-md-3">
            <p>Option de filtre :</p>';
            
            if($this->recherche['on'] == 'Listes'){
                $html .= '<input type="radio" name="on" value="Listes" checked>
                <label for="search">Listes</label>';
            } else{
                $html .= '<input type="radio" name="on" value="Listes">
                <label for="search">Listes</label>';
            }
            
            if($this->recherche['on'] == 'Créateurs'){
                $html .= '<input type="radio" name="on" value="Créateurs" checked>
            <label for="search">Créateurs</label>';
            } else{
                $html .= '<input type="radio" name="on" value="Créateurs">
            <label for="search">Créateurs</label>';
            }
            
            if($this->recherche['on'] == 'Membres'){
                $html .= '<input type="radio" name="on" value="Membres" checked>
            <label for="search">Membres</label>';
            } else{
                $html .= '<input type="radio" name="on" value="Membres">
            <label for="search">Membres</label>';
            }
            
            if($this->recherche['on'] == 'Les deux'){
                $html .= '<input type="radio" name="on" value="Les deux" checked>
            <label for="search">Les deux</label>';
            } else{
                $html .= '<input type="radio" name="on" value="Les deux">
            <label for="search">Les deux</label>';
            }
            
            $html .= '</div>';
            
        } else{
            $html .= '<div class="col col-md-3">
            <p>Option de filtre :</p>
            <input type="radio" name="on" value="Listes">
            <label for="search">Listes</label>
            <input type="radio" name="on" value="Créateurs">
            <label for="search">Créateurs</label>
            <input type="radio" name="on" value="Membres">
            <label for="search">Membres</label>
            <input type="radio" name="on" value="Les deux" checked>
            <label for="search">Les deux</label>
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
            <p>Recherche le mot clé dans la description des listes et/ou infos utilisateurs.</p>
            </div>';
        } else{
            $html .= '<div class="col col-md-3">
            <input type="checkbox" name="deep" value="deep"><label for="search">Recherche profonde</label>
            <p>Recherche le mot clé dans la description des listes et/ou infos utilisateurs.</p>
            </div>';
        }
        
        if(isset($this->recherche['nbReserv'])){
            $html .= '<div class="col col-md-2">
            <p>Filtre par nombre de réservations :</p>
            <input type="number" name="nbReserv" value="' . $this->recherche['nbReserv'] . '">';
            
            if($this->recherche['reserv'] == 'Minimum'){
                $html .= '<input type="radio" name="reserv" value="Minimum" checked>
                <label for="search">Au minimum</label>
                <input type="radio" name="reserv" value="Maximum">
                <label for="search">Au maximum</label>
                <input type="radio" name="reserv" value="Exact">
                <label for="search">Exactement</label>';
                
            } elseif($this->recherche['reserv'] == 'Maximum'){
                $html .= '<input type="radio" name="reserv" value="Minimum">
                <label for="search">Au minimum</label>
                <input type="radio" name="reserv" value="Maximum" checked>
                <label for="search">Au maximum</label>
                <input type="radio" name="reserv" value="Exact">
                <label for="search">Exactement</label>';
                
            } else{
                $html .= '<input type="radio" name="reserv" value="Minimum">
                <label for="search">Au minimum</label>
                <input type="radio" name="reserv" value="Maximum">
                <label for="search">Au maximum</label>
                <input type="radio" name="reserv" value="Exact" checked>
                <label for="search">Exactement</label>';
                
            }
            
            $html .= '</div>';
            
        } else{
            $html .= '<div class="col col-md-2">
            <p>Filtre par nombre de réservations :</p>
            <input type="number" name="nbReserv">
            <input type="radio" name="reserv" value="Minimum">
            <label for="search">Au minimum</label>
            <input type="radio" name="reserv" value="Maximum">
            <label for="search">Au maximum</label>
            <input type="radio" name="reserv" value="Exact" checked>
            <label for="search">Exactement</label>
            </div>';
        }
        
        
        if(isset($this->recherche['nbItem'])){
            $html .= '<div class="col col-md-2">
            <p>Filtre par nombre d\'items :</p>
            <input type="number" name="nbItem" value="' . $this->recherche['nbItem'] . '">';
            
            if($this->recherche['item'] == 'Minimum'){
                $html .= '<input type="radio" name="item" value="Minimum" checked>
                <label for="search">Au minimum</label>
                <input type="radio" name="item" value="Maximum">
                <label for="search">Au maximum</label>
                <input type="radio" name="item" value="Exact">
                <label for="search">Exactement</label>';
                
            } elseif($this->recherche['item'] == 'Maximum'){
                $html .= '<input type="radio" name="item" value="Minimum">
                <label for="search">Au minimum</label>
                <input type="radio" name="item" value="Maximum" checked>
                <label for="search">Au maximum</label>
                <input type="radio" name="item" value="Exact">
                <label for="search">Exactement</label>';
                
            } else{
                $html .= '<input type="radio" name="item" value="Minimum">
                <label for="search">Au minimum</label>
                <input type="radio" name="item" value="Maximum">
                <label for="search">Au maximum</label>
                <input type="radio" name="item" value="Exact" checked>
                <label for="search">Exactement</label>';
                
            }
            
            $html .= '</div>';
            
        } else{
            $html .= '<div class="col col-md-2">
            <p>Filtre par nombre d\'items :</p>
            <input type="number" name="nbItem">
            <input type="radio" name="item" value="Minimum">
            <label for="search">Au minimum</label>
            <input type="radio" name="item" value="Maximum">
            <label for="search">Au maximum</label>
            <input type="radio" name="item" value="Exact" checked>
            <label for="search">Exactement</label>
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
                $html .= '<p>' . $l->titre . '</p>';
            }
            $html .= '</div>';
        }
        
        if(isset($this->membre) and (count($this->membre) > 0)){
            $html .= '<div><h3>Membre / Créateur :</h3>';
            foreach($this->membre as $m){
                $html .= '<p>' . $m->nom . ' ' . $m->prénom . '</p>';
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
        $html .= '<p><input type="text" name="description" class="form-control" aria-describedby="emailHelp" placeholder="Description" required/></p>';
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
        $html = '<section>';
        $i = $this->item;
        $modifier_item = $this->app->urlFor('modifier_item');
        $id=$i->id;

        $html .= '<div class="row"><div class="col-md-2">';
        $html .= '<form method="POST" action="'.$id.'" enctype="multipart/form-data">';
        $html .= '<p><input type="text" name="nom" class="form-control" aria-describedby="emailHelp" placeholder="Nom" value="'.$i->nom.'" autofocus/></p>';
        $html .= '<p><textarea rows="5" cols="50" type="text" name="description" value="">'.$i->descr.'</textarea></p>:';
        //$html .= '<p><input type="text" name="description" class="form-control" aria-describedby="emailHelp" placeholder="Description" value="'.$i->descr.'" /></p>';
        $html .= '<p><input type="number" name="tarif" class="form-control" aria-describedby="emailHelp" placeholder="Tarif" value="'.$i->tarif.'" /></p>';
        $html .= '<p><input type="url" name="url" class="form-control" aria-describedby="emailHelp" placeholder="lien utile" value="'.$i->url.'" /></p>';
        $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>';
        $html .= '<p><input type="file" name="image" id="image" accept=".png, .jpg, .jpeg" /></p>';
        $html .= '<p><input type="text" name="image_url" class="form-control" placeholder="URL de l\'image"/></p>';
        $html .= '<p><button type="submit" class="btn btn-primary" name="supprimer_img" value="supprimer_image">Supprimer l\'image</button></p>';
        $html .= '<p><button type="submit" class="btn btn-primary" name="valider_modif" value="modifier_itesm">Valider modification</button></p>';
        $html .= '</form>';
        $html .= '</div></div>';


        $html .= '</section>';

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
        $html .= '<p><input type="checkbox" name="liste_publique" value="" required > Liste publique</p>'; 
        $html .= '<p><button type="submit" class="btn btn-primary" name="creerUneListe" value="creer_Liste">Créer la liste</button></p>';
        $html .= '</form>';
        $html .= '</div></div></div>';
        $html .= '</section>';

        return $html;
    }
    
    private function modifierListe() {
        $html = '<section>';
        $creer_liste = $this->app->urlFor('modifier_liste');
        $li = $this->liste;

        $html .= '<div class="row"><div class="col-md-8">';
        $html .= '<h1>Vous pouvez modifier les information de la liste ici</h1>';
        $html .= '<div class="row"><div class="col-md-5">';
        $html .= '<form method="POST" action="">';
        $html .= '<p><input type="text" name="titre" class="form-control" aria-describedby="emailHelp" placeholder="Titre" value="'.$li->titre.'" autofocus/></p>';
        $html .= '<p><input type="text" name="descr" class="form-control" aria-describedby="emailHelp" placeholder="Description" value="'.$li->description.'" /></p>';
        $html .= '<p><input type="date" name="date" class="form-control" aria-describedby="emailHelp" placeholder="Date d\'expiration" value="'.$li->expiration.'" /></p>';
        $html .= '<p><button type="submit" class="btn btn-primary" name="valider_modif" value="modifier_liste">Valider modification</button></p>';
        $html .= '</form>';
        $html .= '</div></div></div>';
        $html .= '</section>';

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
                break;
            }
            case 'CREATEURS':{
                $contenu = $this->createurs();
                break;
            }
            case 'VISIONCOMPTES':{
                $contenu = $this->visionComptes();
                $path=".";
                break;
            }
            case 'CONTACT':{
                $contenu = $this->contact();
                break;
            }
            case 'LISTES_CREA':{
                $contenu = $this->affichageListesCrea();
                break;
            }
            case 'LISTES':{
                $contenu = $this->affichageListes();
                $style = '<link rel="stylesheet"  href="' . $path . 'src/css/itemsListes.css"/>';
                break;
            }
            case 'LISTE_CREA':{
                $contenu = $this->affichageListeCrea();
                $style = '<link rel="stylesheet"  href="' . $path . 'src/css/itemsListes.css"/>';
                break;
            }
            case 'LISTE_CO':{
                $contenu = $this->affichageListeInvite();
                $style = '<link rel="stylesheet"  href="' . $path . 'src/css/itemsListes.css"/>';
                $path = '../.';
                break;
            }
            case 'LISTE_INV':{
                $contenu = $this->affichageListeInvite();
                $style = '<link rel="stylesheet"  href="' . $path . 'src/css/itemsListes.css"/>';
                $path = '../.';
                break;
            }
            case 'ITEM_AJOUT':{
                $contenu = $this->ajouterItem();
                $style = '<link rel="stylesheet"  href="' . $path . 'src/css/itemsListes.css"/>';
                $path = '../.';
                break;
            }
            case 'RECHERCHE':{
                $contenu = $this->recherche();
                $style = '<link rel="stylesheet"  href="' . $path . 'src/css/itemsListes.css"/>';
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
                $style = '<link rel="stylesheet"  href="' . $path . 'src/css/itemsListes.css"/>';
                $path = '.';
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
        $lienListesPublic = $this->app->urlFor('listePublic');
        $lienCreateur = $this->app->urlFor('createur');
        $lienContact = $this->app->urlFor('contact');
        
        
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
                  <li class="nav-item active">
                    <a class="nav-link" href=$lienContact>Contact <span class="sr-only">(current)</span></a>
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
}