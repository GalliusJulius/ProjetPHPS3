-- Création de la table :

CREATE TABLE `php_td`.`reservation` ( `idReserv` INT(10000) NOT NULL AUTO_INCREMENT , `idItem` INT(10000) NOT NULL , `idListe` INT(10000) NOT NULL , `idUser` INT(10000) NOT NULL , `message` VARCHAR(1000) NULL DEFAULT NULL , PRIMARY KEY (`idReserv`)) ENGINE = MyISAM;


-- Modification de la table membre à faire (ajout d'un id) :

DELETE FROM `membres` WHERE `membres`.`email` = \'root@local.fr\';
-- Et supprimer tout autre données de la table -- 

ALTER TABLE `membres` CHANGE `email` `idUser` INT(255) NOT NULL AUTO_INCREMENT;

ALTER TABLE membres ADD email varchar(255) NOT NULL;

ALTER TABLE `membres` ADD UNIQUE(`email`);


ALTER TABLE reservation ADD FOREIGN KEY fkItem(idItem) REFERENCES item(id) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE reservation ADD FOREIGN KEY fkListe(idListe) REFERENCES Liste(no) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE reservation ADD FOREIGN KEY fkUser(idUser) REFERENCES membres(idUser) ON DELETE NO ACTION ON UPDATE CASCADE;


ALTER TABLE liste ADD share varchar(255) NOT NULL				-- token de partage --

-- Création d'un token : "$token  =  bin2hex ( random_bytes ( 64 ) ) ;" --

-- On suppose que dans les verisions futur de l'application, on pourra réserver un item à plusieurs --

ALTER TABLE reservation ADD nom varchar(255) NOT NULL;

ALTER TABLE reservation ADD prénom varchar(255) NOT NULL;

-- Placer des valeurs dans les 2 champs précédents --

ALTER TABLE `reservation` CHANGE `idUser` `idUser` INT(255) NULL DEFAULT NULL;

ALTER TABLE liste ADD public BOOLEAN NOT NULL;

-- Changer des valeurs dans cette nouvelle colonne (par défaut à 0 --> false)

ALTER TABLE membres ADD Pseudo varchar(25);
ALTER TABLE membres ADD comp varchar(32) NOT NULL;




