# Douceur de chien

## Log book travail de stage

### Mardi 30  mars 2021

#### Objectif du POC

Pour la réalisation de ce POC, monsieur Mathieu et moi-même avons convenu de réaliser uniquement la partie planning de mon API REST.

C'est à dires, les tables suivantes :

![dbdiagram_poc](.\logbook\dbdiagram_poc.png)

L'objectif est de permettre à l'éducateur canin de créer ses différents créneaux horaires. Ces créneaux horaires (time_slot) pourront être régulier pour une certaine distance(weekly_schedule) ou unique pour un jour(schedule_override), de plus, l'éducateur canin pourra spécifier des distances de vacances(absence) qui devront rendre indisponible tous les créneaux horaires les incluant. Le tout en gérant les différents problèmes de chevauchement que la création de planning pourra entraîner.

Exemple de données de planning allant du 30 mars 2021 jusqu'au 30 avril 2021 :

**weekly_schedule**

![dbdiagram_poc](.\logbook\data_weekly_schedule.png)

**schedule_override**

![dbdiagram_poc](.\logbook\data_schedule_override.png)

**time_slot**

![dbdiagram_poc](.\logbook\data_time_slot.png)

**absence**

![dbdiagram_poc](.\logbook\data_absence.png)

Croquis d'une représentation graphique du planning

![dbdiagram_poc](.\logbook\graph_calendar.png)

L'objectif du POC sera de réaliser les différents endpoints de l'api afin de permettre à l'éducateur canin de réaliser son planning afin de rendre possible la prise de prendre rendez-vous avec celui-ci 

**Création de l'arborescence de l'api REST**

```
api-rest_douceur-de-chien
└───app
│	└───Controllers
│	└───Models
│	└───System
└───public
└───vendor
│   .env
│   bootstrap.php
│   composer.json
│   dbseed.php
```

**app/Controllers**

Dossier contenant les Controllers de l'API qui permettent l'exécution des fonctions CRUD adéquate pour les endpoints.
Exemple de Class Controller permettant les endpoints *users* ou *user/{id}* :

![dbdiagram_poc](.\logbook\diagram_UserController.png)



**app/Models**

Dossier contenant les Models de l'API qui permettent le traitement SQL des données.
Exemple de Class Model permettant de récupérer un utilisateur ou tous les utilisateurs :

![dbdiagram_poc](.\logbook\diagram_UserModel.png)

**app/System**

Dossier contenant les fichiers de système de l'API.
Exemple de Class System permettant la connexion à la base de données :

![dbdiagram_poc](.\logbook\diagram_DatabaseConnector.png)

**public**

Dossier contenant mes fichiers publics.
Exemple : index.php

**vendor**

Dossier contenant les librairies PHP utilisées.
Exemple:  Librairie PHP dotenv qui permet la génération de variables d'environnements. 

**bootstrap.php**

Fichier permettant le chargement des librairies et des variables d'environnements.

**composer.json**

Fichier permettant la mémorisation et la génération des différentes libraires a utilisées.

**dbseed.php**

Fichier permettant d'insérer des données de tests dans la base de données.

### Mercredi 31  mars 2021

**PHP dotenv**

Ajout de la libraire [PHP dotenv](https://github.com/vlucas/phpdotenv) permettant la génération et l'utilisation de variables d'environnements.
Création du fichier .env contenant les variables d'environnement de connexion à la base de données :

* DB_HOST
* DB_PORT
* DB_DATABASE
* DB_USERNAME
* DB_PASSWORD

**System DatabaseConnector**

Création de la class DatabaseConnector permettant la connexion à la base de données.
Pour cette première version, la class récupère les variables d'environnements de connexion et créé un objet PDO avec celles-ci dans son constructeur. Une méthode getConnection() permet de récupérer cette connexion PDO.

**Model User**

Création du premier Model User afin de tester la structure objet de l'API.
Le modèle récupère en paramètre la connexion à la PDO.

Méthodes développées :

* findAll()
  * Récupère toutes les informations de tout les clients hormis le mot de passe et son sel dans un tableau associatif.
* find($id)
  * Similaire à findAll() mais uniquement pour un utilisateur.
* getRole
  * Récupère le rôle d'un utilisateur par rapport à son api_token.

**UserController**

Création du premier Controller UserController.

Méthodes développées :

* processRequest()
  * Permet de traiter la requête correspondant à la méthode spécifié dans le constructeur de l'objet.
    * GET
      * Sans l'attribut "userId" set, la méthode va appeler getAllUsers.
      * Avec l'attribut "userId" set, la méthode va appeler getUser($id).
* getAllUsers()
  * Récupère tout les utilisateurs en format JSON si la demande vient d'un utilisateur avec le rôle 2 (Éducateur canin).
* getUser($id)
  * Récupère l'utilisateur en format JSON correspondant à l'identifiant passé en paramètre. 

**bootstrap.php**

Création du fichier de bootage de l'api, celui-ci permet pour l'instant de :

* Charger les différentes librairie ajoutées avec Composer grâce au fichier autoload.php généré par celui-ci
* Charger les variables d'environnements PHP dotenv
* Créer la connexion avec la base de données.

**index.php**

Point d'entrée des HTTP request de l'api.

* Charge le fichier bootstrap.php
* Ajoute les headers :
  * Access-Control-Allow-Origin: *
    * Permet à n'importe quelle ressource d'accéder aux ressource de l'api.
  * Content-Type: application/json; charset=UTF-8
    * Le type et l'encodage des réponses de l'API
  * Access-Control-Allow-Methods: GET,POST,PATCH,DELETE
    * Permet les méthodes de request de type : GET, POST, PATCH et DELETE
  * Access-Control-Max-Age: 3600
    * La durée maximum de la mise en cache des résultats de request. 
  * Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With
    * Indique quels en-têtes HTTP peuvent être utilisés lors de la request.
* Traite la request pour envoyer la bonne réponse.
  * GET
    * index.php/users => getAllUsers()
    * index.php/user/{id} => getUser{$id}

### Jeudi 01 avril 2021

Ajout de commentaire sur les fichiers : 

* Controllers/UserController.php
* Models/User.php
* System/DatabaseConnector
* Bootstrap.php
* dbseed.php
* index.php

Ajout de type dans tous les paramètres de méthode et de constructeur de l'application.

Création du Model WeeklySchedule avec sa première fonction :

* findAll(bool $isDeleted)
  * Récupère tous les calendriers hebdomadaires encore utilisés ou non.

### Mardi 06 avril 2021

Ajout de fonction dans le Model WeeklySchedule :

* find($id)
  * Récupère un calendrier hebdomadaire grâce à son identifiant.
* insert(array $input)
  * Créé un nouveau calendrier hebdomadaire, le paramètre input correspond pour l'instant à un tableau associatif avec comme clef le nom des colonnes concernées.

Création du Controller WeeklyScheduleController du Model WeeklySchedule.

Méthodes développées :

* processRequest()
* getAllWeeklySchedules()
* getWeeklySchedule(int $id)

Création du Controller ResponseController permettant de retourner les différentes réponses HTTP de l'API.

Méthodes static développées :

* notFoundAuthorizationHeader()
  * Retourne le code 401 Unauthorized ainsi que le message : *L'en-tête d'autorisation n'est pas défini.*
* unauthorizedUser()
  * Retourne le code 401 Unauthorized ainsi que le message : *Vous n'avez pas les permissions.*
* notFoundResponse()
  * Retourne le code 404 Not Found ainsi que le message : *Le serveur n'a pas trouvé la ressource demandée*.
* successfulRequest($result)
  * Retourne le code 200 OK ainsi que la résultat de la réponse en format JSON.

### Mercredi 07 avril 2021

Ajout de fonction dans le Model WeeklySchedule :

* findOverlap(array $input)
  * Récupère les dates qui produisent des problèmes de chevauchement.

Ajout de fonction dans le Controller WeeklyScheduleController :

* createWeeklySchedule()
  * Permet de créer un nouveau calendrier hebdomadaire en vérifiant les points suivants :
    * Attributs obligatoires spécifiés dans la requête (date_valid_from)
    * Format de date valide pour l'attribut date_valid_from et date_valid_to si défini
    * Problème de chevauchement aves les autres dates de la base de données **( /!\ Vérifie pour l'instant uniquement les problèmes de chevauchements avec les deux attributs date_valid_from et date_valid_to définis /!\ )**
* validateWeeklySchedule($input)
  * Contrôle si l'attribut date_valid_from est bien défini
* validateDateFormat($date)
  * Contrôle si une date est dans le bon format (DD-MM-YYYY)

Ajout et modification de fonction dans le Controller ResponseController :

* successfulRequest($result) => successfulGETRequest($result)
* successfulPOSTRequest()
  * Retourne le code 201 Created.

* unprocessableEntityResponse()
  * Retourne le code 422 Unprocessable Entity ainsi que le message : *Attributs invalides.*
* invalidDateFormat()
  * Retourne le code 422 Unprocessable Entity ainsi que le message : *Format de date invalide => (DD-MM-YYYY).*
* overlapProblem
  * Retourne le code 422 Unprocessable Entity ainsi que le message : *Les dates chevauchent d\'autres dates déjà existantes.*

### Jeudi 08 avril 2021

Création du Model ScheduleOverride.

Méthodes développées :

* findAll(bool $isDeleted)
  * Fonctionnement similaire aux précédents Models.
* find(int $id)
  * Fonctionnement similaire aux précédents Models.
* insert(array $input)
  * Fonctionnement similaire aux précédents Models.
* update(int $id, array $input)
  * Fonctionnement similaire aux précédents Models.
* delete(int $id)
  * Fonctionnement similaire aux précédents Models.
* findExistence(string $date)
  * Récupère les dates non-supprimées identiques à celle passée en paramètre afin de vérifier si l'utilisateur ne créé pas deux fois la même date.

Création du Model Absence.

Méthodes développées :

* CRUD similaire aux précédents Models.

Création des Controllers ScheduleOverrideController et AbsenceController qui ont un fonctionnement similaire aux précédents Controllers. 

Création d'un Controller HelperController permettant de contenir les fonctions d'aide de l'api.

* Déplacement de la fonction validateDateFormat($date) dans celui-ci.

Ajout des différents endpoints dans le fichier public : index.php

### Vendredi 09 avril 2021

Création du Model TimeSlot :

* CRUD similaire aux précédents Models.
* findOverlapInWeeklySchedule(array $input)
  * Méthode pour vérifier si la création d'un nouveau créneau horaire ne cause pas de chevauchement avec d'autres créneaux horaire du même calendrier hebdomadaire.

Création du Controller TimeSlotController qui a un fonctionnement similaire aux précédents Controllers. 

Ajout d'une méthode dans le HelperController :

* validateTimeFormat($time)
  * Contrôle si une donnée temporelle est dans le bon format (HH:MM:SS).

Ajout d'une méthode dans le Model WeeklySchedule :

* findActifPermanentSchedule()
  * Vérifie si un calendrier hebdomadaire permanant est déjà existant dans la base de données.

Ajout et modification de méthode dans le ResponseController

* permanentScheduleAlreadyExist()
  * Retourne le code 422 Unprocessable Entity ainsi que le message : *Un calendrier permanent a déjà été créé.*
* invalidTimeFormat()
  * Retourne le code 422 Unprocessable Entity ainsi que le message : *Format de temps invalide => (HH:MM:SS).*
* timeOverlapProblem()
  * Retourne le code 422 Unprocessable Entity ainsi que le message : *Les horaires chevauchent d'autres horaires déjà existants.*
* overlapProblem renommé en dateOverlapProblem

### Lundi 12 avril 2021

Rendez-vous GMeet avec M.Mathieu afin de répondre aux différentes questions :

* Lors de la création d'un TimeSlot, faut-il que la clef étrangère en attribut corresponde bien à un WeeklySchedule ou ScheduleOverride existant ?
  * Résumé de la réponse : Oui, il faut vérifier. Si l'id n'existe pas, il faut retourner un code d'erreur 404 Not Found. Il faut également modifier tout les codes 422 en 400 ou 404 car le code 422 utilise l'extension HTTP WebDAV et de ce fait ne respecte pas le principe architecturaux REST.
* Comment vérifier l'overlap avec un WeeklySchedule existant permanant (lorsque date_valid_from est set mais que date_valid_to est null) ?
  * Résumé de la réponse : Pour commencer, il faut vérifier que la date_valid_from est bien plus petit ou égal à la date_valid_to. Ensuite, il faut tester si le nouveau date_valid_from est plus grand ou égal aux date_valid_from existants et que le date_valid_to est égal à null ou que le nouveau date_valid_from est plus petit ou égal aux date_valid_to existants. 
* Comment doit se comporter l'api lorsqu'un time slot n'est pas supprimé mais que le WeeklySchedule ou le ScheduleOverride est supprimé ?
  * Résumé de la réponse : Lorsque un TimeSlot est avec un WeeklySchedule ou un ScheduleOverride supprimé, alors le time slot n'est pas pris en compte.
* Comment tester de la bonne manière l'overlap des TimeSlots ?
  * Résumé de la réponse : Changement du champ code_day : varchar ("lu","ma","mer",ect...) => int (1,2,3,ect...). Utilisation de la méthode SQL [DAYOFWEEK](https://sql.sh/fonctions/date-heure/dayofweek)  

Développement des points suivants dans le documentation :

* Introduction
* Rappel du cahier des charges
  * Objectifs
  * Environnement de travail
  * Organisation
  * Livrable
* Développement
  * Description des activités
    * Création de la structure de l'API REST
    * Création des différentes class de l'API REST (Création du diagramme de class UML)
* Bilan personnel du travail effectué
* Conclusion

### Mardi 13 avril 2021

Modification du Controller ResponseController

* Modification des code 422 Unprocessable Entity en 400 Bad Request
* Création de la méthode chronologicalDateProblem() Retourne le code 400 Bad Request ainsi que le message : *La date ou l'heure de début est plus récente que la date ou l'heure de fin.*

Modification du Controller HelperController

* Création de la méthode validateChornologicalTime($firsttime, $secondtime)
  * Permet de vérifier si la premier date n'est pas plus récente que la deuxième. 

Modification du Controller TimeSlotController

* Ajout du test de date chronologique dans la méthode de create et d'update.
* Modification de la méthode validateTimeSlot(array $input) afin de vérifier que l'attribut id_weekly_schedule ou id_schedule_override référence bien un champ existant dans la base de données.

Modification du Controller WeeklyScheduleController

* Ajout du test de date chronologique dans la méthode de create et d'update.

Ajout du contrôle chronologique des dates passées dans le body des entpoints dans les Controllers :

* AbsenceController
* WeeklyScheduleController

Modification de la méthode findAll(bool $idDeleted) du Model TimSlot. Dorénavant , la méthode ne prend plus en compte les time slots liés avec un weekly_schedule ou un schedule override supprimé.

Modification de la méthode findOverlap(array $input) du Model WeeklySchedule. Dorénavant, la méthode vérifie toutes les conditions de chevauchement lors d'un insert. Toutefois, la requête SQL génère un warning.

Modification de tout les endpoints afin de respecter les principes architecturaux REST. Dorénavant, tout les endpoints finissent par "s".

### Mercredi 14 avril 2021

Recherche et approfondissement de la requête destinée au dernier endpoint de la partie planning de l'API REST. Pour l'instant, la requête arrive à sortir toutes les dates avec les time slots. Il reste encore à retirer les dates de vacance.

Requête à ce jour développé :

Création des vues virtuelles permettant la génération de date entre (aujourd'hui - 9999 jours) et (aujourd'hui + 365 jours) :

```SQL
CREATE VIEW digits AS
  SELECT 0 AS digit UNION ALL
  SELECT 1 UNION ALL
  SELECT 2 UNION ALL
  SELECT 3 UNION ALL
  SELECT 4 UNION ALL
  SELECT 5 UNION ALL
  SELECT 6 UNION ALL
  SELECT 7 UNION ALL
  SELECT 8 UNION ALL
  SELECT 9;

CREATE VIEW numbers AS
  SELECT
    ones.digit + tens.digit * 10 + hundreds.digit * 100 AS number
  FROM
    digits as ones,
    digits as tens,
    digits as hundreds;

CREATE VIEW dates AS
  SELECT
    SUBDATE(ADDDATE(CURRENT_DATE(),365), number) AS date
  FROM
    numbers;
```

Traitement:

```sql
SELECT time_start,code_day, time_end,date_valid_from, date_valid_to,id_weekly_schedule,id_schedule_override,schedule_override.date_schedule_override, 
IF(dates.date IS NOT NULL, dates.date, schedule_override.date_schedule_override)

FROM time_slot
LEFT JOIN weekly_schedule
ON weekly_schedule.Id = time_slot.id_weekly_schedule

LEFT JOIN schedule_override
ON schedule_override.id = time_slot.id_schedule_override

LEFT JOIN dates
ON DAYOFWEEK(date) = time_slot.code_day 
AND date BETWEEN date_valid_from 
AND IF(date_valid_to IS NULL, DATE_ADD(NOW(), INTERVAL 365 DAY), date_valid_to) 

WHERE weekly_schedule.is_deleted = 0
OR schedule_override.is_deleted = 0


ORDER BY DATE
```



Création du rapport de stage en LaTeX initialement rédigé sur Google Docs.

* Utilisation du paquet LaTeX [rest-api](https://www.ctan.org/pkg/rest-api) permettant d'afficher les endpoints d'une API REST

Envoie d'un mail à M. Mathieu afin de répondre aux points suivants :

* Est-ce que mon rapport de stage répond bien aux attentes ?
* Est-ce qu'une requête qui fonctionne, mais qui génère des avertissements du côté SQL est acceptable ou non ?

### Jeudi 15 avril 2021

Modification du champ code_day de la base de données initialement de type varchar en tinyint. 

* "dim" => 1
* "lun" => 2
* "mar" => 3
* "mer" => 4
* "jeu" => 5
* "ven" => 6
* "sam" => 7

Modification du code d'erreur de la méthode unauthorizedUser()

* 401 Unauthorized => 403 Forbidden

Modification du Model TimeSlot :

* Ajout de la méthode generateViews() qui permet de générer les différentes vues virtuelles pour la génération de date.
* Ajout de la méthode findPlanningTimeSlots() qui permet la récupération de tous les créneaux horaires en prenant en compte les vacances, la requête finale ressemble à ça :

```SQL
SELECT time_start,time_end, IF(dates.date IS NOT NULL, dates.date, so.date_schedule_override) AS date

FROM time_slot AS ts
LEFT JOIN weekly_schedule AS ws
ON ws.Id = ts.id_weekly_schedule

LEFT JOIN schedule_override AS so
ON so.id = ts.id_schedule_override

LEFT JOIN dates
ON DAYOFWEEK(dates.date) = ts.code_day 
AND dates.date BETWEEN ws.date_valid_from 
AND IF(ws.date_valid_to IS NULL, DATE_ADD(NOW(), INTERVAL 365 DAY), ws.date_valid_to) 

WHERE ts.is_deleted = 0 AND (so.is_deleted = 0 OR ws.is_deleted = 0)
AND (SELECT COUNT(*) 
FROM absence AS ab
WHERE IF(so.date_schedule_override IS NULL,dates.date,so.date_schedule_override) BETWEEN ab.date_absence_from AND ab.date_absence_to LIMIT 1) = 0

ORDER BY DATE;
```

Création de la méthode getPlanningTimeSlots() dans le Controller TimeSlotController.

Modification de la requête de vérification de chevauchement de calendrier hebdomadaire qui générait une avertissement coté SQL afin que cela ne soit plus le cas.

Finalisation de la documentation technique.

## Log book travail de diplôme 

### Vendredi 20 novembre 2020

Rencontre physique avec le client afin de répondre à différentes questions pour la réalisation de la version 1 du cahier des charges.

**Question posé :**

Comment procéder de la meilleure des façons pour la création et le prise en charge d'un nouveau client ?

Quelles sont les données personnelles du client ?

Quelles sont les données personnelles du chien ?

Comment rechercher les clients dans l'application ?

À quel moment les différents mails doivent-ils être envoyé ? 

Quelles sont les informations du client que l'éducateur canin doit avoir la possibilité de consulter ?

Quelles sont les informations que le client doit avoir la possibilité de consulter ?

**Résumé de la discussion**

**Scénario de prise en charge d'un nouveau client**

**Étape 1 : Procédure d'ajout d'un nouveau client par téléphone**

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

**Étape 2 : Rencontre physique avec le client**

1. L'éducateur canin se rend au domicile du client à la date spécifié lors de l'appel téléphonique. 
2. Il va se rendre sur l'application mobile et se connecter avec ses identifiants.
3. Il va rechercher le client grâce à son nom et accéder à sa fiche client précédemment créée lors de l'appel téléphonique.
4. Il va montrer les données personnelles du client et lui demander une vérification de celle-ci.
5. Si elles sont fausses, modification de celle-ci.
6. Si elles sont correctes, l'éducateur canin devra prendre une photo du chien ainsi que de rentrer manuellement ou avec un lecteur RFID communiquant en Bluetooth avec l'application, les 15 chiffres du code de la puce sous-cutanée du chien.
7. Il pourra ensuite sauvegarder cette version final de la fiche client.

**Scénario de rendez-vous avec le client**

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



**Fonctionnalité disponible pour l'éducateur canin**

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

**Fonctionnalité disponible pour le client**

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

**Question posé :**

Calendrier natif ou intégrer à l'application ?

Qui peut modifier les informations personnelles d'un client ?

Quand faut-il envoyer le mail lors de l'ajout de document ?

**Résumé de la discussion**

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

![dateTestPlanningFirstUser](.\logbook\dateTestPlanningFirstUser.png)

Deuxième éducateur canin :

![dateTestPlanningSecondUser](.\logbook\dateTestPlanningSecondUser.png)

Troisième éducateur canin :

![dateTestPlanningThirdUser](.\logbook\dateTestPlanningThirdUser.png)

Documentation et recherche de la fonctionnalité de test proposé par Postman afin de tester mon API REST.

### Mardi 20 avril 2021

Création des tests unitaires avec l'outil Postman des différents endpoints développés lors du travail de stage. 
[Lien de la documentation Postman](https://documenter.getpostman.com/view/9321886/TzJuAd3h)

Tests des endpoints du modèle Absence :

![unitsTestsAbsence](.\logbook\unitsTestsAbsence.PNG)

Tests des endpoints du modèle ScheduleOverride :

![unitsTestsScheduleOverride](.\logbook\unitsTestsScheduleOverride.PNG)

Tests des endpoints du modèle WeeklySchedule :

![unitsTestsWeeklySchedule](.\logbook\unitsTestsWeeklySchedule.PNG)

Tests des endpoints du modèle TimeSlot :

![unitsTestsTimeSlot](.\logbook\unitsTestsTimeSlot.PNG)

Pour tester la plupart des scénarios d'utilisations de mon API REST, j'ai rajouté dans le script dbseed.php d'autres données permettant de vérifier la maximum de scénarios d'utilisations possibles.

Dorénavant, avant le développement des futurs endpoints de l'API REST, je réaliserais leurs différents tests en essayant de couvrir le maximum de scénarios d'utilisations.   

Suite à la discussion avec M. Mathieu, je compte réaliser la documentation de mon travail de diplôme en 2 parties : 

* Une documentation théorique 
* Une documentation technique

Début de la documentation théorique en format LaTeX en utilisant l'éditeur en ligne Overleaf. Les points traités ont été les suivants :

* Résumé
* Rappel du cahier des charges (partiel)

### Mercredi 21 avril 2021

Création des tests unitaires du endpoint permettant la récupération du planning final de l'éducateur canin authentifié.

![unitsTestsTimeSlot](.\logbook\unitsTestsPlanning.PNG)

Modification des commentaires des modèles de planning (Absence, ScheduleOverride, WeeklySchedule et TimeSlot) qui ne contenait pas le commentaire de paramètre `$idEducator` . 
Modification de toute les méthode `findAll(bool $deleted,int $idEducator)` des modèles de planning afin de réaliser un bindparam sur le paramètre `$deleted`. 

Avant :

```SQL
SELECT id, date_absence_from, date_absence_to, description
FROM absence
WHERE is_deleted=".(int)$isDeleted."
AND id_educator = :ID_EDUCATOR;
```

Après : 

```sql
SELECT id, date_absence_from, date_absence_to, description
FROM absence
WHERE is_deleted= :DELETED
AND id_educator = :ID_EDUCATOR;
```

Suppression du champs `password_salt` dans la table `user` de la base de données afin de suivre l'avertissement de PHP 7.
*Avertissement : L'option Salt a été désapprouvée à partir de PHP 7.0.0. Il est maintenant préférable d'utiliser simplement le sel qui est généré par défaut.* [source](https://www.php.net/manual/fr/function.password-hash.php)
En effet, PHP recommande de ne plus utiliser de salt personnel mais d'utiliser la méthode PHP `password_hash`. La méthode prend en paramètre différents algorithme de hachage, je compte utiliser la constante PHP `PASSWORD_DEFAULT` qui utilise l'algorithme bcrypt. Constante évoluant avec son temps afin de trouver des algorithmes de plus en plus robuste, PHP nous conseille également de stocker le résultat dans une colonne de la base de données qui peut contenir au moins 60 caractères. J'ai donc modifier la taille de type VARCHAR du champs `password_hash` initialement 45 en 60.

Création d'un champs `user_id_educator` dans la table `appoitment` lié à l'id de la table `user` de la base de données afin de permettre aux clients de l'application de prendre rendez-vous avec l'éducateur canin de leurs choix car l'application doit maintenant le permettre.

Ajout d'un code à chaque test unitaire de l'API REST. Exemple de code :

[ABS-GA1] 

* ABS => Modèle Absence
* GA => Get all
* 1 => Numéro de test

[SCH-UO2]

* SCH => Modèle ScheduleOverride
* UO => Update one
* 2 => Numéro de test

Création de la Class Constants dans le fichier `app/system/Constants.php` permettant l'utilisation des différentes constantes de l'application.

Création des tests unitaires des endpoints du modèle User :

![unitsTestsTimeSlot](.\logbook\unitsTestsUser.PNG)

Développement du modèle User et du contrôleur UserController permettant un CRUD nécessitant les droits administrateurs.

Blocage pour la conceptualisation des endpoints qui devront permettre de récupérer uniquement les informations de l'utilisateur grâce à son api token (Données de rendez-vous, informations personnelles, documents, informations du/des chiens). En effet, la structure de l'API REST développée jusqu'à là est difficilement adaptable.  

### Jeudi 22 avril 2021

Modification de toutes les méthodes update des différents contrôleurs déjà développés de l'API, de la méthode de vérification de format de date et des différents tests unitaires. En effet, les endpoints d'update de l'API demandait obligatoirement la présence des tout les champs dans le body afin de ne pas créer d'incohérence ou de problème. Dorénavant, les endpoints d'update peuvent maintenant modifier 1 ou plusieurs champs en utilisant la méthode PHP `array_replace($array1, $array2)`.

1. Récupère la ressource grâce à son identifiant dans la base
2. Remplace la ressource actuel avec la nouvelle avec la méthode `array_replace`
3. Update le résultat dans la base de données

Modification du script dbseed.php. Dorénavant, le script insère 3 chiens appartenant à un 1 utilisateur différents.
Création des tests unitaires des endpoints du modèle Dog :

![unitsTestsTimeSlot](.\logbook\unitsTestsDog.PNG)

Modification de toutes les méthodes `find($id)` de l'API REST afin que celle-ci retourne uniquement un résultat objet et non un objet avec un tableau d'un élément. 

Modification du script dbseed.php. Dorénavant, le script insère 3 documents appartenant à un 1 utilisateur différents.
Création des tests unitaires des endpoints du modèle Document :

![unitsTestsTimeSlot](.\logbook\unitsTestsDocument.PNG)

Développement du modèle Document et du contrôleur DocumentController permettant un CRUD nécessitant les droits administrateurs.

Modification du script dbseed.php. Dorénavant, le script insère 3 rendez-vous appartenant entre un client et un éducateur canin.
Création des tests unitaires des endpoints du modèle Appoitment :

![unitsTestsTimeSlot](.\logbook\unitsTestsAppoitment.PNG)

Développement du modèle Appoitment et du contrôleur AppoitmentController permettant un CRUD nécessitant les droits administrateurs.

Maintenant que tout les endpoints de base de la partie clientèle ont été développés. Réflexion par rapport aux endpoints qui devront être modifiés afin de répondre aux réels besoins de application. En effet, je vais dorénavant procéder à une réflexion cas par cas des endpoints qui devront être utilisables par les clients et non uniquement par les administrateurs (éducateurs canins).

**Cas d'utilisation de l'API numéro 1 : Inscription et connexion de l'utilisateur autonome**

![unitsTestsTimeSlot](.\diagram\UseCaseInscription.png)

Modification du endpoint [POST] api/v1/users afin qu'il soit accessible pour les utilisateurs non-authentifiés. Lors de la création de fiche client via l'appel téléphonique, l'éducateur canin ne spécifiera pas le mot de passe de l'utilisateur, de ce fait, le endpoint devra permettre de générer un mot de passe automatique et de l'envoyer par mail au client afin qu'il puisse récupérer son api token grâce à ces identifiants.

Création et utilisation de la méthode permettant de générer un mot de passe aléatoire :

```php
public static function generateRandomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $password = array();
    $alphaLength = strlen($alphabet) - 1; 
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $password[] = $alphabet[$n];
    }
    return implode($password);
}
```

### Vendredi 23 avril 2021

Importation de la libraire PHPMailer avec la commande `composer require phpmailer/phpmailer` et création de la méthode permettant l'envoie de mail simpliste avec le protocole SMTP.

```PHP
public static function sendMail(string $message,string $emailRecipient)
    {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                                                   
        $mail->Host       = getenv('SMTP_HOST');                  
        $mail->SMTPAuth   = true;                                
        $mail->Username   = getenv('SMTP_USERNAME');                  
        $mail->Password   = getenv('SMTP_PASSWORD');                               
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
        $mail->Port       = 587;                                   

        //Recipients
        $mail->setFrom('noreplyfrom@hotmail.com', 'No reply');
        $mail->addAddress($emailRecipient); 

        //Content
        $mail->isHTML(true);                             
        $mail->Subject = 'Douceur de Chien';
        $mail->Body    = $message;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    }
```

Discussion avec M. Mathieu de la structure de l'API REST qui était un point bloquant et qui commençait à créer beaucoup de problème de pérennité pour le projet. Je vais dorénavant réfléchir et réaliser une nouvelle structure plus simpliste et compréhensible. 

```
v1
└───app
│	└───DataAccessObject
│	└───Controllers
│	└───Models
│	└───System
└───public
	└───user
	│	└───index.php
	└───dog
	│	└───index.php
	└───document
	│	└───index.php
	└───ect..

```

Dans cette nouvelle version, mes modèles dans le dossier Models vont devenir des Data Access Object (DAO).
`Models/User` => `DataAccessObject/DAOUser`
`Models/Dog` => `DataAccessObject/DAODog`
`Models/Document` => `DataAccessObject/Document`
ect...

Mes contrôleurs réaliseront les mêmes fonctionnalités qu'auparavant à quelques point prêt :

1. Récupération des données transmissent par les différents endpoint
2. Création du/des modèles correspondants
3. Vérification des données et retourner les erreurs quand cela est nécessaire.
4. Utilisation du DAO correspondant afin de procéder au traitement final avec la base de données

Création des nouveaux modèles qui seront une représentation objet des tables de la base de données. 
La table `absence` aura un modèle `Absence.php` avec des variables d'instance correspondante aux champs de la table :

```PHP
$absence = new Absence($id,$date_absence_from,$date_absence_to,$description,$is_deleted,$id_educator);
//OU
$absence = new Absence()
$absence->id = $id;
$absence->date_absence_from = $date_absence_from;
$absence->date_absence_to = $date_absence_to;
$absence->description = $description;
$absence->is_deleted = $is_deleted;
$absence->id_educator = $id_educator;
```

**[Suite] Cas d'utilisation de l'API numéro 1 : Inscription et connexion de l'utilisateur autonome**

Création du nouveau fichier d'entrée pour les endpoints utilisateurs respectant la nouvelle structure discutée avec M. Mathieu.
Modification de l'ancien contrôleur afin qu'il réponde aux nouvelles demandes du nouveau fichier d'entrée.
Création du Data Access Object DAOUser qui était anciennement mon modèle.
Création du nouveau modèle permettent de représenter les données de ma table user de manière objet.

Envoie d'un mail à M. Mathieu afin de lui montrer les modifications structurelles de mon API REST afin d'être sûr d'être sur la bonne voie. Une question à propos de l'emplacement des futurs endpoints spéciaux comme celui permettant la connexion a également été posé. 

### Lundi 26 avril 2021

Réponse de M. Mathieu du mail envoyé le vendredi 23 avril. Pour ce qui est de la structure, celle-ci a été dans l'ensemble validé. En effet, la structure est dorénavant mieux organisé et plus facilement lisible. Une remarque par rapport à la validation des champs lors du endpoint da création d'utilisateur m'a été soumise par M. Mathieu. La réponse étant un peu flou pour moi, j'ai renvoyé un mail afin d'éclaircir cette remarque.

Modification des tests unitaires Postman des endpoints utilisateurs. Changement du format de test pour les verbs GET. Auparavant, les tests unitaires vérifiaient si les informations de retour correspondaient exactement à une certaine donnée :

```javascript
pm.test("The right user was obtained", () => {
  const responseJson = pm.response.json();
  pm.expect(responseJson.id).to.eql(1);
  pm.expect(responseJson.email).to.eql("sophiedubois766@gmail.com");
  pm.expect(responseJson.firstname).to.eql("Sophie");
  pm.expect(responseJson.lastname).to.eql("Dubois");
  pm.expect(responseJson.phonenumber).to.eql("0792349172");
  pm.expect(responseJson.address).to.eql("Route de la fraise 15 1268 Genève");
  pm.expect(responseJson.api_token).to.eql(null);
  pm.expect(responseJson.code_role).to.eql(null);
  pm.expect(responseJson.password_hash).to.eql(null);
});
```



Dorénavant, ces tests vérifient si la structure de données ainsi que les différents type attendu sont bien présent. Exemple du test permettant la vérification de la structure de données du endpoint retournant toutes les informations des clients :

```javascript
pm.test("The data structure of the response is correct", () => {
  pm.response.to.have.jsonSchema({
      "type": "array",
      "items": [{
          "type": "object",
          "properties": {
              "id" : {"type" : "integer"},
              "email" : {"type" : "string"},
              "firstname" : {"type" : "string"},
              "lastname" : {"type" : "string"},
              "phonenumber" : {"type" : "string"},
              "address" : {"type" : "string"},
              "api_token" : {"type" : "null"},
              "code_role" : {"type" : "null"},
              "password_hash" : {"type" : "null"}
          },
          "required": ["id","email","firstname","lastname","phonenumber","address","api_token","code_role","password_hash"]
      }]
  })
});
```



Finalisation des endpoints utilisateurs, les endpoints développés jusqu'à là sont :

* `POST api/v1/users` pour créer un nouveau client, si le champ "password" n'est pas définit, alors l'API génère un mot de passe aléatoire et l'envoie par mail au client. Endpoint accessible par n'importe quel type d'utilisateur.
* `GET api/v1/users` pour retourner les informations de tout les clients. Endpoint accessible uniquement par les administrateurs.
* `GET api/v1/users/{idUser}` pour retourner les informations d'un utilisateur.  Endpoint accessible uniquement par les administrateurs.
* `PATCH api/v1/users/{idUser}` pour modifier les informations d'un utilisateur. Endpoint accessible uniquement par les administrateurs.
* `DELETE api/v1/users{idUser}` pour supprimer un utilisateur.  Endpoint accessible uniquement par les administrateurs.
* `GET api/v1/uesrs/me` pour récupérer l'intégralité des informations de l'utilisateur authentifié (pour l'instant, uniquement avec les informations de son/ses chiens). Endpoint accessible par les utilisateurs authentifiés.

Création des tests unitaire et des endpoints dog permettant un CRUD, les endpoints actuellement développés et testé sont :

* `POST api/v1/dogs` pour créer un nouveau chien. Endpoint accessible uniquement par les administrateurs.
* `GET api/v1/dogs` pour retourner les informations de tout les chiens. Endpoint accessible uniquement par les administrateurs.
* `GET api/v1/dogs/{idDog}` pour retourner les informations d'un chien.  Endpoint accessible uniquement par les administrateurs.
* `PATCH api/v1/dogs/{idDog}` pour modifier les informations d'un chien. Endpoint accessible uniquement par les administrateurs.
* `DELETE api/v1/dogs{idDog}` pour supprimer un utilisateur.  Endpoint accessible uniquement par les administrateurs.

Recherche et réflexion pour la réalisation des endpoints permettant l'upload et le download des photos de chien.

Développement des points suivant dans le rapport :

* Résumé
* Abstract
* La société Douceur de Chien
* Rappel du cahier des charges
* Organisation
  * Gestion de projet
  * Format de documentation
* Développement
  * API REST
    * API
    * Principes architecturaux REST
      * HTTP Verbs and Requests
      * Code de réponse HTTP
      * Format de réponse

### Mardi 27 avril 2021