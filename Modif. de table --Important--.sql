-- Création de la table :

CREATE TABLE `reservation` ( `idReserv` INT(255) NOT NULL AUTO_INCREMENT , `idItem` INT(255) NOT NULL , `idListe` INT(255) NOT NULL , `idUser` INT(255) NOT NULL , `message` VARCHAR(1000) NULL DEFAULT NULL , PRIMARY KEY (`idReserv`)) ENGINE = MyISAM;


-- Modification de la table membre à faire (ajout d'un id) :

DELETE FROM `membres`;
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

ALTER TABLE membres ADD Pseudo varchar(255);
ALTER TABLE membres ADD comp varchar(255) NOT NULL;

**Mise à jour (Victor)

remettre la colonne comp de membre en 255 (va bloquer la création de compte sinon)

table de laison entre les liste et membre : 

-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 04 jan. 2019 à 23:37
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `morelier1u`
--

-- --------------------------------------------------------

--
-- Structure de la table `liste_membres`
--

DROP TABLE IF EXISTS `liste_membres`;
CREATE TABLE IF NOT EXISTS `liste_membres` (
  `liste_no` int(255) NOT NULL,
  `membres_id` int(255) NOT NULL,
  PRIMARY KEY (`liste_no`,`membres_id`),
  KEY `fkMembre` (`membres_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Il n'y a pas de modèles à créer (voir sur la doc eloquent les pivots et les relations many to many pour plus d'infos)



-- Création de la table des relations entre les personnes : --

CREATE TABLE `php_td`.`Relation` ( `idUser1` INT(255) NOT NULL, `idUser2` INT(255) NOT NULL, PRIMARY KEY (`idUser1`, `idUser2`)) ENGINE = MyISAM;


-- Modification de la table Item : --
ALTER TABLE item ADD cagnotte boolean NOT NULL;
ALTER TABLE `item` CHANGE `cagnotte` `cagnotte` BOOLEAN NULL DEFAULT NULL;

--Création de la table des particpations à une cagnotte : --

CREATE TABLE `php_td`.`participation` ( `idParticip` INT(255) NOT NULL AUTO_INCREMENT , `idCagnotte` INT(255) NOT NULL , `idUser` INT(255) NULL DEFAULT NULL , `message` VARCHAR(255) NOT NULL , `nom` VARCHAR(255) NOT NULL , `prenom` VARCHAR(255) NOT NULL , `montant` INT(255) NOT NULL , PRIMARY KEY (`idParticip`)) ENGINE = MyISAM;


ALTER TABLE `participation` CHANGE `idCagnotte` `idItem` INT(255) NOT NULL;
ALTER TABLE participation ADD FOREIGN KEY fItem(idItem) REFERENCES item(idItem) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE liste ADD COLUMN message VARCHAR(1000);





ALTER TABLE `membres` CHANGE `Prénom` `Prenom` VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;


-- Ne pas utiliser :

/**********
ALTER TABLE `membres` CHANGE `idUser` `idUser` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `liste` CHANGE `user_id` `user_id` INT(11) NOT NULL;
ALTER TABLE `membres` CHANGE `idUser` `idUser` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `liste` CHANGE `user_id` `user_id` INT(11) UNSIGNED NOT NULL;


ALTER TABLE liste ADD FOREIGN KEY fkListe(user_id) REFERENCES membres(idUser) ON DELETE NO ACTION ON UPDATE CASCADE;
*********/
