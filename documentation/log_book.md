# Douceur de chien 

## Log book

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

### Mardi 24 novembre 2020

Attribution du temps aux tâches et attribution des tâches aux jours.

Ajout du planning prévisionnel au cahier des charges.

### Mercredi 16 décembre 2020

Modification du cahier des charges suite à des discussions avec les professeurs M.Bonvin et M.Garchery, les différents points traités sont les suivants :

- L'application mobile devient une PWA (Progressive web app)
- Suppression de la fonctionnalité de lecture de donnée RFID par Bluetooth

### Samedi 9 janvier 2021

Rencontre physique avec le client afin de répondre au maximum aux exigences de celui-ci.

#### Question posé :

Calendrier natif ou intégrer à l'application ?

Qui peut modifier les informations personnelles d'un client ?

Quand faut-il envoyer le mail lors de l'ajout de document ?

#### Résumé de la discussion

Le client désire centraliser tous ses rendez-vous professionnels avec un calendrier intégré à l'application afin de ne pas mélanger les rendez-vous pro et les rendez-vous privés.

Les informations personnelles des clients pourront être modifiées uniquement par l'administrateur(éducateur canin).

Un mail devra être envoyé lors de la création/ajout de document. Celui-ci contiendra en pièce-jointe le/les documents en question.

### Lundi 19 avril 2021

Commencement officiel du travail de diplôme. Conférence avec M. Garcia afin de discuter du déroulement et du règlement du travail de diplôme.

Importation du travail effectué dans le POC simulant le travail de stage de l'année 2020 (Gestion de planning de l'unique éducateur canin de l'application).

Rendez-vous physique en C109 avec M. Mathieu afin de poser différentes questions par rapport au déroulement du travail de diplôme. Les questions posées étaient :

* Sous quel format devons-nous rédiger la documentation technique du travail de diplôme ?
  * Réponse : Nous sommes plutôt libre du format (MarkDown, Word, Latex, autres). Nous avons discuté de la documentation technique et avons convenu de la réaliser en MarkDown la documentation réellement technique dans le dépôt distant GIT,  et dans un second document LaTeX ou Word, la documentation théorique.
* Est-il possible d'organiser des rendez-vous réguliers entre nous et M. Mathieu ?
  * Réponse : Cela n'a pas encore été validé, mais les jours de rencontres se dérouleront soit les mardi matin, soit les vendredi.
* Faut-il que je réalise des tests unitaires pour mon API REST ?
  * Réponse : Oui, réaliser des tests pour mon API REST est une bonne idée. Nous avons appris l'existence de la solution de test d'automatisation [Katalon](https://www.katalon.com/) permettant l'exécution de test automatique sur les futures vues de nos applications. M. Mathieu m'a également conseillé de tester mon API REST avec l'outil [Postman](https://www.postman.com/).
* Faut-il que permette à mon API REST d'être utilisé par plusieurs éducateur canin ?
  * Réponse : Oui, c'est une bonne idée qui permettrait de rendre l'application plus complète.

Ajout d'une vérification du format du code day lors de la création ou la modification d'un time slot.

* Création de la méthode `validateCodeDayFormat(string $code_day)`dans le HelperController permettant de vérifier si le code day est entre 1 inclus et 7 inclus.
* Création de la méthode de réponse `invalidCodeDayFormat() `dans le ResponseController 
  * La méthode renvoi le code erreur 400 Bad Request avec le message :  Format de jour invalide => (1 jusqu\'à 7, dimanche = 1).

Modification de la base de données afin de permettre la création, l'utilisation et la gestion de planning pour plusieurs éducateur canin. Les 4 tables permettant ces fonctionnalités détiennent dorénavant un champs `id_educator` :

![mcd_planning_modified](.\logbook\mcd_planning_modified.PNG)

Modification de tout les modèles et contrôleurs concernés.

* Les méthodes concernés des modèles contiennent maintenant en paramètre => `int $idEducator` afin de permettre aux différentes requêtes SQL de traiter uniquement les données pour un éducateur canin.

Envoie de mail à M. Mathieu afin de poser la question suivante : Faut-il réaliser un Trello pour notre travail de diplôme malgré le fait qu'on soit seul à le réaliser ? 

Modification du script dbseed.php. Dorénavant, en plus de la création des 10 utilisateurs de test, le script permet d'insérer dans la base des données des données de test pour 3 éducateurs canins détenant 3 exemples de planning différents.

Premier éducateur canin :

![mcd_planning_modified](.\logbook\dateTestPlanningFirstUser.png)

Deuxième éducateur canin :

![mcd_planning_modified](.\logbook\dateTestPlanningSecondUser.png)

Troisième éducateur canin :

![mcd_planning_modified](.\logbook\dateTestPlanningThirdUser.png)

Documentation et recherche de la fonctionnalité de test proposé par Postman afin de tester mon API REST.

### Mardi 20 avril 2021

Création des tests unitaires avec l'outil Postman des différents endpoints développés lors du travail de stage. 
[Lien de la documentation Postman](https://documenter.getpostman.com/view/9321886/TzJuAd3h)

Tests des endpoints du modèle Absence :

![mcd_planning_modified](.\logbook\unitsTestsAbsence.PNG)

Tests des endpoints du modèle ScheduleOverride :

![mcd_planning_modified](.\logbook\unitsTestsScheduleOverride.PNG)

Tests des endpoints du modèle WeeklySchedule :

![mcd_planning_modified](.\logbook\unitsTestsWeeklySchedule.PNG)

Tests des endpoints du modèle TimeSlot :

![mcd_planning_modified](.\logbook\unitsTestsTimeSlot.PNG)

Pour tester la plupart des scénarios d'utilisations de mon API REST, j'ai rajouté dans le script dbseed.php d'autres données permettant de vérifier la maximum de scénarios d'utilisations possibles.

Dorénavant, avant le développement des futurs endpoints de l'API REST, je réaliserais leurs différents tests en essayant de couvrir le maximum de scénarios d'utilisations.   

Suite à la discussion avec M. Mathieu, je compte réaliser la documentation de mon travail de diplôme en 2 parties : 

* Une documentation théorique 
* Une documentation technique

Début de la documentation théorique en format LaTeX en utilisant l'éditeur en ligne Overleaf. Les points traités ont été les suivants :

* Résumé
* Rappel du cahier des charges (partiel)

### Mercredi 21 avril 2021