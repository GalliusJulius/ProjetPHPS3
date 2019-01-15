CREATE TABLE IF NOT EXISTS Liste
(
    no INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    expiration DATE,
    token VARCHAR(255),
    share VARCHAR(255) NOT NULL,
    public BOOLEAN NOT NULL,
    message TEXT
);

CREATE TABLE IF NOT EXISTS Item
(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    liste_id INT(11) NOT NULL,
    nom TEXT NOT NULL,
    descr TEXT,
    img TEXT,
    url TEXT,
    tarif DEC(5, 2)
);

CREATE TABLE IF NOT EXISTS Membres
(
    idUser int(255) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    pseudo VARCHAR(255),
    comp VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS liste_membres
(
    liste_no int(255) NOT NULL,
    membres_id int(255) NOT NULL,
    PRIMARY KEY (liste_no, membres_id),
    KEY fkMembre (membres_id) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS reservation
( 
    idReserv INT(255) NOT NULL AUTO_INCREMENT,
    idItem INT(255) NOT NULL,
    idListe INT(255) NOT NULL,
    idUser INT(255) NOT NULL,
    message VARCHAR(1000) NULL DEFAULT NULL, 
    PRIMARY KEY (idReserv)
) ENGINE = MyISAM;

ALTER TABLE membres ADD UNIQUE(email);

ALTER TABLE reservation ADD FOREIGN KEY fkItem(idItem) REFERENCES item(id) ON DELETE NO ACTION ON UPDATE CASCADE;
ALTER TABLE reservation ADD FOREIGN KEY fkListe(idListe) REFERENCES Liste(no) ON DELETE NO ACTION ON UPDATE CASCADE;
ALTER TABLE reservation ADD FOREIGN KEY fkUser(idUser) REFERENCES membres(idUser) ON DELETE NO ACTION ON UPDATE CASCADE;
ALTER TABLE reservation ADD nom varchar(255) NOT NULL;
ALTER TABLE reservation ADD prenom varchar(255) NOT NULL;
ALTER TABLE reservation CHANGE idUser idUser INT(255) NULL DEFAULT NULL;

CREATE TABLE IF NOT EXISTS Relation
( 
    idUser1 INT(255) NOT NULL, 
    idUser2 INT(255) NOT NULL, 
    PRIMARY KEY (idUser1, idUser2)
) ENGINE = MyISAM;

ALTER TABLE item ADD cagnotte boolean NULL DEFAULT NULL;

CREATE TABLE IF NOT EXISTS participation ( 
    idParticip INT(255) NOT NULL AUTO_INCREMENT, 
    idCagnotte INT(255) NOT NULL, 
    idUser INT(255) NULL DEFAULT NULL, 
    message VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL, 
    prenom VARCHAR(255) NOT NULL, 
    montant INT(255) NOT NULL, 
    PRIMARY KEY (idParticip)
) ENGINE = MyISAM;


ALTER TABLE participation CHANGE idCagnotte idItem INT(255) NOT NULL;
ALTER TABLE participation ADD FOREIGN KEY fItem(idItem) REFERENCES item(idItem) ON DELETE NO ACTION ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS amis (
  idDemande int(255) NOT NULL,
  idRecu int(255) NOT NULL,
  statut varchar(255) NOT NULL DEFAULT 'Attente',
  PRIMARY KEY (idDemande, idRecu),
  KEY idRecu (idRecu,idDemande)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `item` (`id`, `liste_id`, `nom`, `descr`, `img`, `url`, `tarif`) VALUES
(1, 2, 'Champagne', 'Bouteille de champagne + flutes + jeux à gratter', 'champagne.jpg', '', '20.00'),
(2, 2, 'Musique', 'Partitions de piano à 4 mains', 'musique.jpg', '', '25.00'),
(3, 2, 'Exposition', 'Visite guidée de l’exposition ‘REGARDER’ à la galerie Poirel', 'poirelregarder.jpg', '', '14.00'),
(4, 3, 'Goûter', 'Goûter au FIFNL', 'gouter.jpg', '', '20.00'),
(5, 3, 'Projection', 'Projection courts-métrages au FIFNL', 'film.jpg', '', '10.00'),
(6, 2, 'Bouquet', 'Bouquet de roses et Mots de Marion Renaud', 'rose.jpg', '', '16.00'),
(7, 2, 'Diner Stanislas', 'Diner à La Table du Bon Roi Stanislas (Apéritif /Entrée / Plat / Vin / Dessert / Café / Digestif)', 'bonroi.jpg', '', '60.00'),
(8, 3, 'Origami', 'Baguettes magiques en Origami en buvant un thé', 'origami.jpg', '', '12.00'),
(9, 3, 'Livres', 'Livre bricolage avec petits-enfants + Roman', 'bricolage.jpg', '', '24.00'),
(10, 2, 'Diner  Grand Rue ', 'Diner au Grand’Ru(e) (Apéritif / Entrée / Plat / Vin / Dessert / Café)', 'grandrue.jpg', '', '59.00'),
(11, 0, 'Visite guidée', 'Visite guidée personnalisée de Saint-Epvre jusqu’à Stanislas', 'place.jpg', '', '11.00'),
(12, 2, 'Bijoux', 'Bijoux de manteau + Sous-verre pochette de disque + Lait après-soleil', 'bijoux.jpg', '', '29.00'),
(19, 0, 'Jeu contacts', 'Jeu pour échange de contacts', 'contact.png', '', '5.00'),
(22, 0, 'Concert', 'Un concert à Nancy', 'concert.jpg', '', '17.00'),
(23, 1, 'Appart Hotel', 'Appart’hôtel Coeur de Ville, en plein centre-ville', 'apparthotel.jpg', '', '56.00'),
(24, 2, 'Hôtel d\'Haussonville', 'Hôtel d\'Haussonville, au coeur de la Vieille ville à deux pas de la place Stanislas', 'hotel_haussonville_logo.jpg', '', '169.00'),
(25, 1, 'Boite de nuit', 'Discothèque, Boîte tendance avec des soirées à thème & DJ invités', 'boitedenuit.jpg', '', '32.00'),
(26, 1, 'Planètes Laser', 'Laser game : Gilet électronique et pistolet laser comme matériel, vous voilà équipé.', 'laser.jpg', '', '15.00'),
(27, 1, 'Fort Aventure', 'Découvrez Fort Aventure à Bainville-sur-Madon, un site Accropierre unique en Lorraine ! Des Parcours Acrobatiques pour petits et grands, Jeu Mission Aventure, Crypte de Crapahute, Tyrolienne, Saut à l\'élastique inversé, Toboggan géant... et bien plus encore.', 'fort.jpg', '', '25.00');

INSERT INTO `liste` (`no`, `user_id`, `titre`, `description`, `expiration`, `token`, `share`, `public`, `message`) VALUES
(1, 1, 'Pour fêter le bac !', 'Pour un week-end à Nancy qui nous fera oublier les épreuves. ', '2018-06-27', 'nosecure1', 'nosecure01', 0, ''),
(2, 2, 'Liste de mariage d\'Alice et Bob', 'Nous souhaitons passer un week-end royal à Nancy pour notre lune de miel :)', '2018-06-30', 'nosecure2', 'nosecure02', 0, NULL),
(3, 3, 'C\'est l\'anniversaire de Charlie', 'Pour lui préparer une fête dont il se souviendra :)', '2017-12-12', 'nosecure3', 'nosecure03', 0, NULL);