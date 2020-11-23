# DDC'App
## Work book

### Vendredi 20 novembre 2020

Rencontre physique avec le client afin de répondre à différentes questions pour la réalisation de la version 1 du cahier des charges.

#### Question posé :

Comment procéder de la meilleure des façons pour la création et le prise en charge d'un nouveau client ?

Quelles sont les données personnelles du client ?

Quelles sont les données personnelles du chien ?

Comment rechercher les clients dans l'application ?

À quel moment les différents mails doivent-ils être envoyé ? 

Quelles sont les informations du client que l'éducateur canin doit avoir la possibilité de consulter ?

Quelles sont les informations que le client doit avoir la possibilité de consulter ?

#### Résumé de la discussion

##### Scénario de prise en charge d'un nouveau client
###### Étape 1 : Procédure d'ajout d'un nouveau client par téléphone

1. Le client appel l'éducateur canin avec son téléphone car il a besoin de ces services.
2. L'éducateur canin va se rendre sur l'application mobile et se connecter avec ses identifiants.
3. Il va se rendre sur l'interface de création d'une nouvelle fiche client.
4. Il va y renter les informations personnelles du client transmises par téléphone :
   - Nom du client
   - Prénom du client
   - Téléphone du client
   - Adresse mail du client
   - Adresse du domicile du client
   - Date de naissance du chien
   - Race du chien
   - Sexe du chien
   - Nom du chien
5. Il aura accès à son calendrier personnel afin de visualiser à quelle date il peut se rendre au domicile du client.
6. Le client ainsi que l'éducateur se mettront d'accord sur la date du rendez-vous.
7. L'éducateur canin sélectionnera cette date dans le calendrier.
8. Une fois la fiche client avec la date du premier rendez-vous remplis, un mail sera envoyé au client afin qu'il puisse créer son compte dans l'application afin d'avoir accès à différentes fonctionnalités.

###### Étape 2 : Rencontre physique avec le client 

1. L'éducateur canin se rend au domicile du client à la date spécifié lors de l'appel téléphonique. 
2. Il va se rendre sur l'application mobile et se connecter avec ses identifiants.
3. Il va rechercher le client grâce à son nom et accéder à sa fiche client précédemment créée lors de l'appel téléphonique.
4. Il va montrer les données personnelles du client et lui demander une vérification de celle-ci.
5. Si elles sont fausses, modification de celle-ci.
6. Si elles sont correctes, l'éducateur canin devra prendre une photo du chien ainsi que de rentrer manuellement ou avec un lecteur RFID communiquant en Bluetooth avec l'application, les 15 chiffres du code de la puce sous-cutanée du chien.
7. Il pourra ensuite sauvegarder cette version final de la fiche client.

##### Scénario de rendez-vous avec le client

1. L'éducateur canin peut à tout moment lors d'un rendez-vous, accéder à la fiche du client afin de pouvoir y rentrer différentes données :
   1. Note du cours sous format texte (accessible uniquement par l'éducateur).
   2. Note du cours sous format graphique (accessible uniquement par l'éducateur).
   3. Note récapitulatif du cours (accessible par l'éducateur ainsi que le client).
   4. Si le rendez-vous est le premier, alors le client doit depuis l'application de l'éducateur :
      1. Choisir le forfait qu'il désire.
      2. Ajouter sa signature depuis l'application.
      3. Visualiser la version final des conditions d'inscriptions. 
      4. Validée s'il est d'accord en cochant une case "Lu et approuvé".
      5. Les conditions d'inscription sous format PDF ainsi qu'une génération automatique d'une facture sous format PDF sera ajouté au dossier partagé du client.



##### Fonctionnalité disponible pour l'éducateur canin

- Connexion à l'application.
- Accès au calendrier de ces rendez-vous.
- Affichage de tous les clients avec photo du chien/nom et prénom du client.
- Recherche spécifique d'un client par nom ou depuis un scan de puce sous-cutanée canine.
- Accès aux informations personnelles d'une fiche client depuis la recherche spécifique ou le calendrier de rendez-vous.
  - Nom du client
  - Prénom du client
  - Etc...
  - Document PDF du client (condition d'inscriptions, fiche de cours, etc...)
- Création préliminaire d'une fiche client (*Étape 1 : Procédure d'ajout d'un nouveau client par téléphone*)
- Accès ou création de contenue séance d'une fiche client depuis la recherche spécifique ou le calendrier de rendez-vous.
  - Rendez-vous 1
    - Note du cours sous format texte
    - Note du cours sous format graphique
    - Note récapitulatif du cours
    - (si premier cours, alors ajout conditions d'inscription, facture, etc...)
  - Rendez-vous 2
    - ...

##### Fonctionnalité disponible pour le client

- Inscription à l'application depuis le mail envoyé lors de la fin de *procédure d'ajout d'un nouveau client par téléphone*
- Connexion à l'application
- Accès au calendrier de ces rendez-vous
- Accès à ces informations personnelles (avec contrat signé)
- Accès à ces différents contenues séances (note récapitulatif du cours ainsi que affiche PDF du cours)

### Lundi 23 novembre 2020

Feedback du cahier des charges de Monsieur Garcia lors du cours du lundi matin via Google Meet. Retour positif de celui-ci, mais il manque le planning prévisionnel.

Création des différentes tâches du planning prévisionnel sans attribution de temps.

Modification du modèle de données :

- Ajout d'une champ api_token dans la table user permettant l'authentification à l'API Rest d'un utilisateur.