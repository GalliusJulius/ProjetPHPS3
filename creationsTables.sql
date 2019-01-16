CREATE TABLE IF NOT EXISTS liste
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

CREATE TABLE IF NOT EXISTS item
(
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    liste_id INT(11) NOT NULL,
    nom TEXT NOT NULL,
    descr TEXT,
    img TEXT,
    url TEXT,
    tarif DEC(5, 2)
);

CREATE TABLE IF NOT EXISTS membres
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
    CONSTRAINT fkListe FOREIGN KEY (liste_no) REFERENCES Liste(no),
    CONSTRAINT fkMembres FOREIGN KEY (membres_id) REFERENCES Membres(idUser)
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

INSERT INTO `Liste` (`no`, `user_id`, `titre`, `description`, `expiration`, `token`, `share`, `public`, `message`) VALUES
(1, 1, 'Pour fêter le bac !', 'Pour un week-end à Nancy qui nous fera oublier les épreuves. ', '2018-06-27', 'nosecure1', 'nosecure01', 0, ''),
(2, 2, 'Liste de mariage d\'Alice et Bob', 'Nous souhaitons passer un week-end royal à Nancy pour notre lune de miel :)', '2018-06-30', 'nosecure2', 'nosecure02', 0, NULL),
(3, 3, 'C\'est l\'anniversaire de Charlie', 'Pour lui préparer une fête dont il se souviendra :)', '2017-12-12', 'nosecure3', 'nosecure03', 0, NULL),
(4, 1, 'Titre', 'Description de la liste', '2019-01-19', '3fab9d7ab43c17d2803d263c50cc8b19c12a0ab060140125b577faeae6b9a6f5', 'ecc34b0a09044bfe5c7ca5722675f72cf8321ec0b0234cb83315f1849a7cc963', 1, NULL),
(5, 2, 'Liste numéro 1', 'Ceci est la liste numéro 1', '2019-01-20', '465d0541045ab7b2e26d2803d4fadfa498248f73a77da14f7d8dcb87e58685c8', 'bf6f7963f9556bd0d79f47a480ea53f2e726abb26c5959bf8f58c5f4e73a613f', 1, 'Voici les items de ma liste'),
(6, 2, 'Liste numéro 2', 'Description de la liste numéro 2', '2019-01-18', 'f7d3f5ccf1aba34dcb379ea89d1ab8d0e2b46ca5c1e82216a5d1b79b0b44baf3', '40495fb125010036cfb86f6ce1a3f3b4f62c012ae54021446b1ad03af5f7e306', 1, 'Voici ma liste avec les deux items');

INSERT INTO `liste_membres` (`liste_no`, `membres_id`) VALUES
(1, 2),
(2, 1),
(3, 1),
(3, 2);

INSERT INTO `membres` (`idUser`, `nom`, `prenom`, `mdp`, `email`, `pseudo`, `comp`) VALUES
(2, 'CHAUMONT', 'Tristan', '$2y$10$OmEiP8VMMEGHvSpvQi.8AuzJlUyIB2MJaPE1vS0ffksiyZOwDOLme', 'tristan@root.fr', 'Tristan', 'cbe0db1e2f7a844e6883a400181fdfa23dd2dea540df4e78e3c24bfdf0d11418'),
(3, 'Test1Nom', 'Test1Prenom', '$2y$10$0DgIGYB65CdZEXDwY3J1HeRPBRZlclZl0ZWyQj/.QLFfJTLy92A2e', 'test1@mail.fr', 'Test1', '8ae33c13499c530a34c5cc61fd4fa1cafef2a3de9954118dd162291ae15b5600'),
(4, 'Test1Nom', 'Test2Prenom', '$2y$10$MyQ0PRcB.cLkR9OzD6uoqO5tAXYQkXDMwvKTaK5XsWkwOQSVJDAEa', 'test2@mail.fr', 'Test2', 'f3b6a42260bff104fd62bfe0356cb78aa0ce56d2668576b114184faf662f79e3');

INSERT INTO `participation` (`idParticip`, `idItem`, `idUser`, `message`, `nom`, `prenom`, `montant`) VALUES
(1, 33, 3, 'Voici 10 euros', 'Test1Nom', 'Test1Prenom', 10);

INSERT INTO `amis` (`idDemande`, `idRecu`, `statut`) VALUES
(2, 3, 'ok'),
(4, 2, 'Attente');