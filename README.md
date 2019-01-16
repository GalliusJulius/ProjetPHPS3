# WishList - S3B (CHAUMONT - GAUCHOTTE - LUC - MORELIERE)

## Pseudos Github

| Pseudos                              | Etudiants |
| -------------                        | ------------- |
| Totor57 / GalliusJulius              | Victor Morelière  |
| Luc21u                               | Tristan Luc  |
| Tristan CHAUMONT / tristan-chaumont  | Tristan Chaumont  |
| Rémi Gauchotte / RemiG3              | Rémi Gauchotte  |


 

## Base de données

Nous avons créé un fichier `creationTables.sql` à exécuter avant de tester le projet car il contient les différentes tables que nous avons ajoutées et les attributs supplémentaires de certaines tables déjà présentes initialement.
Concernant les diagrammes de séquences, certains ne sont plus en accord avec les routes et méthodes que nous avons réalisées suite aux modifications faites sur le code.




## Ajouts sur la base de données initiale



### Table *“Liste”*
#### Attributs ajoutés

- *“share”* : correspond au token de la liste partagée
- *“public”* : booléen à 0 si la liste n’est pas publique, à 1 dans le cas contraire
- *“message”* : message qu’un créateur peut ajouter sur sa liste



### Table *“Item”*
#### Attribut ajouté

- *“cagnotte”* : cagnotte récoltée pour l’item voulu



### Nouvelle table *“Membres”* : table regroupant les utilisateurs qui se sont inscrits sur le site
#### Attributs

- *“idUser”* : id de l’utilisateur inscrit sur le site **[PRIMARY KEY]**
- *“nom”* : nom de l’utilisateur
- *“prenom”* : prénom de l’utilisateur
- *“mdp”* : mot de passe hashé de l’utilisateur
- *“email”* : email de l’utilisateur
- *“comp”* : sel du mot de passe



### Nouvelle table *“Liste_membres”* : jonction entre les listes et les membres
#### Attributs

- *“liste_no”* : id de la liste **[PRIMARY KEY]** **[FOREIGN KEY sur la table liste]**
- *“membres_id”* : id du membre **[PRIMARY KEY]** **[FOREIGN KEY sur la table membres]**



### Nouvelle table *“participation”* : table des utilisateurs ayant participé à une cagnotte
#### Attributs

- *“idParticip”* : id de la table participation **[PRIMARY KEY]**
- *“idItem”* : id de d’item **[FOREIGN KEY sur la table item]**
- *“idUser”* : id de l’utilisateur qui a participé à la cagnotte de l’item **[FOREIGN KEY sur la table Membres]**
- *“message”* : message que l’utilisateur peut laisser lors de sa participation
- *“nom”* : nom de l’utilisateur
- *“prenom”* : prénom de l’utilisateur
- *“montant”* : montant de la participation de l’utilisateur



### Nouvelle table *"relation”* : table définissant quels sont les utilisateurs qui sont amis
#### Attributs

- *“idUser1”* : id de l’utilisateur 1 **[PRIMARY KEY]**
- *“idUser2”* : id de l’utilisateur 2 **[PRIMARY KEY]**



### Nouvelle table *“amis”* : table gérant les demandes d’amis
#### Attributs

- *“idDemande”* : id de l’utilisateur qui a fait la demande d’amis **[PRIMARY KEY]** **[FOREIGN KEY sur la table membres]**
- *“idRecu”* : id de l’utilisateur qui a reçu la demande d’amis **[PRIMARY KEY]** **[FOREIGN KEY sur la table membres]**
- *“statut”* : statut de la demande d’amis. Initialement à “Attente” et passe à “Ok” si la demande est acceptée.



### Nouvelle table *“reservation”* : table gérant les réservations d’item
#### Attributs

- *“idReserv”* : id de la réservation **[PRIMARY KEY]**
- *“idItem”* : id de l’item réservé **[FOREIGN KEY sur la table item]**
- *“idListe”* : id de la liste où l’item réservé est stocké **[FOREIGN KEY sur la table liste]**
- *“idUser”* : id de l’utilisateur qui a réservé l’item **[FOREIGN KEY sur la table membres]**
- *“message”* : message que l’utilisateur qui réserve l’item laisse
- *“nom”* : nom de l’utilisateur qui réserve
- *“prenom”* : prénom de l’utilisateur qui réserve




## Tableau de bord

- Le tableau de bord est représenté sous un Google Sheets avec : 
  - l’ordre de priorité des fonctionnalités (de 1 à 5)
  - le numéro de la fonctionnalité (colorée en vert si elle est terminée, en jaune si elle est cours de réalisation, en rouge si elle n’est pas terminée)
  - le nom de la fonctionnalité
  - les personnes qui ont effectué ces fonctionnalités
  - l’url associée à la fonctionnalité
  - le nom du contrôleur associé à la fonctionnalité
  - la méthode associée à la fonctionnalité
  - les fonctionnalités où l’on a réalisé un diagramme de séquence



## Fonctionnalités ajoutées

### *“Supprimer une liste”*

- Suppression d’une liste dans la page de sélection des listes



### *“Afficher le profil d’une personne”*

- Afficher le profil d’un utilisateur ou de l’un de ses amis dans une page dédiée



### *“Ajouter des contacts (demande d’ajout)”*

- Pouvoir envoyer une demande d’ajout en amis à un autre utilisateur
- L’autre utilisateur doit accepter avant qu’on puisse le voir apparaître dans ses contacts



### *“Consulter les listes d’un contact”*

- Pouvoir consulter les listes d’un de ses contacts
- Consultation possible uniquement si les deux utilisateurs sont amis



### *“Supprimer un contact (et décliner une demande d’ajout)”*

- Supprimer un de ses contacts dans la page où ils sont listés
- Décliner une demande d’ajout lors de sa réception



### *“Rechercher une liste ou un contact (recherche globale)”*

- Recherche d’une liste ou d’un contact via la barre de recherche dans le nav



### *“Rechercher une liste parmi toutes celles accessibles (recherche paramétrée)”*

- Une fois la recherche dans le nav effectuée, on peut faire une recherche paramétrée avec des options de filtrage



### *“Rechercher seulement un contact (recherche paramétrée)”*

- On peut choisir un créateur, un membre via les options de filtrage



### *"Appliquer une politique de password"*

- On applique une politique de password via passwordPolicy
- Demander à l'utilisateur d'entrer un mot de passe d'au moins 6 caractères avec au moins une majuscule, une minuscule et un chiffre


## Informations supplémentaires

- On suppose qu’une personne ayant un compte est un membre. Un membre peut participer à des listes et peut également en créer, à ce moment il obtient le statut de *“créateur”*. 
- Si un utilisateur non connecté fait des actions qu’il n’est pas censé pouvoir effectuer, il est redirigé vers la page de connexion. (par exemple : modifier les informations de son compte, ajouter un ami, …)
