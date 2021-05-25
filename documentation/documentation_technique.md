# Travail de diplôme Douceur de Chien - Documentation technique

- [Travail de diplôme Douceur de Chien - Documentation technique](#travail-de-diplôme-douceur-de-chien---documentation-technique)
  - [API REST](#api-rest)
    - [Arborescence](#arborescence)
      - [app/Models](#appmodels)
      - [app/DataAccessObject](#appdataaccessobject)
      - [app/Controllers](#appcontrollers)
      - [app/System](#appsystem)
      - [public](#public)
      - [storage](#storage)
      - [bootstrap.php](#bootstrapphp)
    - [Structure](#structure)
    - [Base de données](#base-de-données)
    - [Tests unitaires](#tests-unitaires)
      - [Format de code](#format-de-code)
      - [Syntaxe](#syntaxe)
    - [Composer](#composer)
    - [Librairies](#librairies)
      - [PHPMailer](#phpmailer)
      - [Dompdf](#dompdf)
    - [Endpoints](#endpoints)
      - [Headers](#headers)
      - [Listes des endpoints](#listes-des-endpoints)
  - [PWA](#pwa)
    - [Vuejs](#vuejs)
      - [Arborescence](#arborescence-1)
      - [Structure](#structure-1)
      - [Composants](#composants)
      - [Librairies](#librairies-1)
        - [Vue Router](#vue-router)
        - [Vuex](#vuex)
        - [Axios](#axios)
        - [Jquery](#jquery)
        - [BootstrapVue](#bootstrapvue)
        - [Responsive-Sketchpad](#responsive-sketchpad)
        - [FullCalendar](#fullcalendar)
        - [Webpack](#webpack)

## API REST

### Arborescence

```
api/v1
│
└── app
│   └── Models
│   └── DataAccessObject
│   └── Controllers
│   └── System
└── public
└── storage
└── vendor
└── .env
└── bootstrap.php
```

#### app/Models

Le dossier Models contient les modèles de l'API REST. Chaque modèle est une représentation objet de sa table de base de données correspondante. La création de ces modèles me permet d'utiliser les données de ma base de données de manière objet.
Exemple de la classe `Dog` représentant la table `dog` de la base de données :

![dateTestPlanningSecondUser](./diagram/umletino/dogModel.png)

![dateTestPlanningSecondUser](./diagram/dogTable.PNG)

#### app/DataAccessObject

Le dossier DataAccessObject contient les data access object (DAO) de l'API REST. Ces DAO contiennent toutes les méthodes permettant un CRUD sur sa table de base de données correspondante. Les méthodes des DAO fonctionnent de manière à créer ou récupérer des modèles afin de respecter un maximum la structure objet de l'API REST.
Exemple de la classe `DAODog` :

![dateTestPlanningSecondUser](./diagram/umletino/dogDAO.png)

#### app/Controllers

Le dossier Controllers contient les contrôleurs de l'API REST, comme leur nom l'indique. Le but des contrôleurs est de contrôler les différents cas d'utilisation et d'autorisation d'accès en utilisant, s'il le faut, les DAO afin de communiquer avec la base de données et en retournant les différents codes HTTP et messages en format JSON. 
Exemple de la classe `DogController` :

![dateTestPlanningSecondUser](./diagram/umletino/dogController.png)

Dans ce dossier résident également les contrôleurs `ResponseController` et `HelperController`. Le `ResponseController` permet de retourner toutes les différentes réponses HTTP. Le `HelperController` permet l'utilisation de méthode dite d'aide et qui n'aurait pas leur place dans un contrôleur basique.
Classes `ResponseController` et `HelperController` :

![dateTestPlanningSecondUser](./diagram/umletino/ResponseHelperController.png)

#### app/System

Le dossier System contient la classe `DatabaseConnector` qui permet la connexion à la base de données en récupérant les variables d'environnements *PHP dotenv* et la classe `Constants` permettant de stocker les différentes constantes de l'API REST.
Classes `DatabaseConnector` et `Constants` :

![dateTestPlanningSecondUser](./diagram/umletino/databaseConnectorConstants.png)

#### public

Le dossier public contient les différents fichiers d'entrées de l'API REST. Les fichiers d'entrées récupèrent le `verb HTTP` d'une requête HTTP afin de pouvoir exécuter les bonnes méthodes des contrôleurs. Ces fichiers s'occupent également d'attribuer les headers et le body si nécessaire. 

#### storage

Dossier contenant les différents fichiers uploadés de l'API REST, comme les documents PDF ou les photos de chiens par exemple. 

#### bootstrap.php

Fichier de bootage de l'API REST inclus dans tous les fichiers d'entrées, celui-ci permet de : 

* Charger les dépendances PHP du dossier vendor
* Charger les variables d'environnements
* Créer la connexion à la base de données

### Structure

![dateTestPlanningSecondUser](./diagram/drawio/system.svg)

### Base de données

![dateTestPlanningSecondUser](./img/database.png)

La base de données que j'ai développé et utilisé se décompose en deux parties.
La partie supérieure, donc les tables : `appoitment`, `user`, `document` et `dog` concernent toutes les données en lien avec les clients de l'application.
Tandis que la partie inférieure, donc les tables : `weekly_schedule`,`time_slot`, `schedule_override` et `absence` concernent les données de planning des éducateurs canins de la société.

La table `appoitment` contient les informations des rendez-vous entre un éducateur canin et un client :

<table>
    <tr>
    	<th  style="text-align:center;" COLSPAN="5">appoitment</th>
    </tr>
    <tr>
        <th style="text-align:center;">NOM</th>
        <th style="text-align:center;">TYPE</th>
        <th style="text-align:center;">NOT NULL</th>
        <th style="text-align:center;">DESCRIPTION</th>
    </tr>
    <tr>
        <td>datetime_appoitment</td>
        <td>DATETIME</td>
        <td style="text-align:center;">X</td>
        <td>la date ainsi que l'heure du rendez-vous entre un client et un éducateur canin.</td>
    </tr>
    <tr>
        <td>duration_in_hour</td>
        <td>INT</td>
        <td style="text-align:center;">X</td>
        <td>La durée en heure du rendez-vous.</td>
    </tr>
    <tr>
        <td>note_text</td>
        <td>TEXT</td>
        <td style="text-align:center;"></td>
        <td>Les notes textuelles du rendez-vous.</td>
    </tr>
    <tr>
        <td>note_graphical_serial_id</td>
        <td>VARCHAR</td>
        <td style="text-align:center;"></td>
        <td>L'identifiant de série de la note graphique uploadé sur le serveur du rendez-vous.</td>
    </tr>
    <tr>
        <td>summary</td>
        <td>TEXT</td>
        <td style="text-align:center;"></td>
        <td>Le résumé d'un rendez-vous.</td>
    </tr>
    <tr>
        <td>datetime_deletion</td>
        <td>DATETIME</td>
        <td style="text-align:center;"></td>
        <td>La date ainsi que l'heure de la suppression du rendez-vous.</td>
    </tr>
    <tr>
        <td>user_id_customer</td>
        <td>INT</td>
        <td style="text-align:center;">X</td>
        <td>L'identifiant de l'utilisateur de type client du rendez-vous.</td>
    </tr>
    <tr>
        <td>user_id_educator</td>
        <td>INT</td>
        <td style="text-align:center;">X</td>
        <td>L'identifiant de l'utilisateur de type éducateur canin du rendez-vous.</td>
    </tr>
    <tr>
        <td>user_id_deletion</td>
        <td>INT</td>
        <td style="text-align:center;"></td>
        <td>L'identifiant de l'utilisateur ayant supprimé le rendez-vous.</td>
    </tr>
</table>


*Remarques*

Les données des champs `datetime_deletion` et `user_id_deletion` sont uniquement ajoutées lors de la suppression non-définitive du rendez-vous par un éducateur canin ou un client.

---

La table `user` contient les informations des différents utilisateurs :

<table>
    <tr>
    	<th  style="text-align:center;" COLSPAN="5">user</th>
    </tr>
    <tr>
        <th style="text-align:center;">NOM</th>
        <th style="text-align:center;">TYPE</th>
        <th style="text-align:center;">NOT NULL</th>
        <th style="text-align:center;">DESCRIPTION</th>
    </tr>
    <tr>
        <td>email</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>L'adresse e-mail de l'utilisateur.</td>
    </tr>
    <tr>
        <td>firstname</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>Le prénom de l'utilisateur.</td>
    </tr>
    <tr>
        <td>lastname</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>Le nom de famille de l'utilisateur.</td>
    </tr>
    <tr>
        <td>phonenumber</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>Le numéro de téléphone de l'utilisateur.</td>
    </tr>
    <tr>
        <td>address</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>L'adresse du domicile de l'utilisateur.</td>
    </tr>
    <tr>
        <td>api_token</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>Le token d'authentification de l'utilisateur.</td>
    </tr>
    <tr>
        <td>code_role</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>Le code du rôle de l'utilisateur (client, administrateur, autre).</td>
    </tr>
    <tr>
        <td>password_hash</td>
        <td>VARCHAR</td>
        <td style="text-align:center;"></td>
        <td>Le hash du mot de passe de l'utilisateur.</td>
    </tr>
</table>

*Remarques*

La table ne contient pas de sel pour le mot de passe car depuis PHP 7.0.0, il est conseillé d'utiliser le sel généré par défaut.

---

La table `document` contient les informations des documents des utilisateurs :

<table>
    <tr>
    	<th  style="text-align:center;" COLSPAN="5">document</th>
    </tr>
    <tr>
        <th style="text-align:center;">NOM</th>
        <th style="text-align:center;">TYPE</th>
        <th style="text-align:center;">NOT NULL</th>
        <th style="text-align:center;">DESCRIPTION</th>
    </tr>
    <tr>
        <td>document_serial_id</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>L'identifiant de série du document uploadé sur le serveur.</td>
    </tr>
    <tr>
        <td>type</td>
        <td>ENUM</td>
        <td style="text-align:center;">X</td>
        <td>Le type du document (conditions d'inscription, poster, autre).</td>
    </tr>
    <tr>
        <td>user_id</td>
        <td>INT</td>
                <td style="text-align:center;">X</td>
        <td>L'identifiant de l'utilisateur propriétaire du document.</td>
    </tr>
</table>


---

La table `dog` contient les informations des chiens des utilisateurs :

<table>
    <tr>
    	<th  style="text-align:center;" COLSPAN="5">dog</th>
    </tr>
    <tr>
        <th style="text-align:center;">NOM</th>
        <th style="text-align:center;">TYPE</th>
        <th style="text-align:center;">NOT NULL</th>
        <th style="text-align:center;">DESCRIPTION</th>
    </tr>
    <tr>
        <td>name</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>Le nom du chien.</td>
    </tr>
    <tr>
        <td>breed</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>La race du chien.</td>
    </tr>
    <tr>
        <td>sex</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>Le sexe du chien.</td>
    </tr>
    <tr>
        <td>picture_serial_id</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>L'identifiant de série de la photo du chien uploadé sur le serveur.</td>
    </tr>
   	<tr>
        <td>chip_id</td>
        <td>VARCHAR</td>
        <td style="text-align:center;"></td>
        <td>L'identifiant de la puce sous-cutanée du chien.</td>
    </tr>
    <tr>
        <td>user_id</td>
        <td>INT</td>
        <td style="text-align:center;">X</td>
        <td>L'identifiant de l'utilisateur propriétaire du chien.</td>
    </tr>
</table>


---

La table `weekly_schedule` contient les informations des calendriers hebdomadaires des éducateurs canins :

<table>
    <tr>
    	<th  style="text-align:center;" COLSPAN="5">weekly_schedule</th>
    </tr>
    <tr>
        <th style="text-align:center;">NOM</th>
        <th style="text-align:center;">TYPE</th>
        <th style="text-align:center;">NOT NULL</th>
        <th style="text-align:center;">DESCRIPTION</th>
    </tr>
    <tr>
        <td>date_valid_from</td>
        <td>DATE</td>
        <td style="text-align:center;">X</td>
        <td>La date de début du calendrier hebdomadaire.</td>
    </tr>
    <tr>
        <td>date_valid_to</td>
        <td>DATE</td>
        <td style="text-align:center;"></td>
        <td>La date de fin du calendrier hebdomadaire.</td>
    </tr>
    <tr>
        <td>is_deleted</td>
        <td>TINYINT</td>
        <td style="text-align:center;">X</td>
        <td>Le statut du calendrier hébdomadaire.</td>
    </tr>
    <tr>
        <td>id_educator</td>
        <td>INT</td>
        <td style="text-align:center;">X</td>
        <td>L'identifiant de l'éducateur canin propriétaire du calendrier hébdomadaire.</td>
    </tr>
</table>

*Remarques*

Le système s'assure qu'il n'y ait pas de chevauchement entre les différentes lignes non supprimées de cette table (`date_valid_from` et `date_valid_to`) appartenant au même éducateur canin.
Une seule ligne non supprimée d'un éducateur canin peut avoir comme valeur `null` le champ `date_valid_to`. Cette ligne correspondra donc à l'unique calendrier permanant d'un éducateur canin.

---

La table `schedule_override` contient les informations des exceptions d'horaires des éducateurs canins :

<table>
    <tr>
    	<th  style="text-align:center;" COLSPAN="5">schedule_override</th>
    </tr>
    <tr>
        <th style="text-align:center;">NOM</th>
        <th style="text-align:center;">TYPE</th>
        <th style="text-align:center;">NOT NULL</th>
        <th style="text-align:center;">DESCRIPTION</th>
    </tr>
    <tr>
        <td>date_schedule_override</td>
        <td>DATE</td>
        <td style="text-align:center;">X</td>
        <td>La date de l'exception d'horaire.</td>
    </tr>
    <tr>
        <td>is_deleted</td>
        <td>TINYINT</td>
        <td style="text-align:center;">X</td>
        <td>Le statut de l'exception d'horaire.</td>
    </tr>
    <tr>
        <td>id_educator</td>
        <td>INT</td>
        <td style="text-align:center;">X</td>
        <td>L'identifiant de l'éducateur canin propriétaire de l'exception d'horaire.</td>
    </tr>
</table>

*Remarques*

Le système s'assure qu'il n'y ait pas deux fois la même date entre les différentes lignes non supprimées de cette table (`date_schedule_override`) appartenant au même éducateur canin.

---

La table `time_slot` contient les informations des créneaux horaires des éducateurs canins :

<table>
    <tr>
    	<th  style="text-align:center;" COLSPAN="5">time_slot</th>
    </tr>
    <tr>
        <th style="text-align:center;">NOM</th>
        <th style="text-align:center;">TYPE</th>
        <th style="text-align:center;">NOT NULL</th>
        <th style="text-align:center;">DESCRIPTION</th>
    </tr>
    <tr>
        <td>code_day</td>
        <td>TINYINT</td>
        <td style="text-align:center;">X</td>
        <td>Le code correspondant à un jour de la semaine du créneau horaire.</td>
    </tr>
    <tr>
        <td>time_start</td>
        <td>TIME</td>
        <td style="text-align:center;">X</td>
        <td>L'heure de début du créneau horaire.</td>
    </tr>
    <tr>
        <td>time_end</td>
        <td>TIME</td>
        <td style="text-align:center;">X</td>
        <td>L'heure de fin du créneau horaire.</td>
    </tr>
        <tr>
        <td>is_deleted</td>
        <td>TINYINT</td>
        <td style="text-align:center;">X</td>
        <td>Le statut du créneau horaire.</td>
    </tr>
    <tr>
        <td>id_weekly_schedule</td>
        <td>INT</td>
        <td style="text-align:center;"></td>
        <td>L'identifiant du calendrier hebdomadaire.</td>
    </tr>
    <tr>
        <td>id_schedule_override</td>
        <td>INT</td>
        <td style="text-align:center;"></td>
        <td>L'identifiant de l'exception d'horaire.</td>
    </tr>
    <tr>
        <td>id_educator</td>
        <td>VARCHAR</td>
        <td style="text-align:center;">X</td>
        <td>L'identifiant de l'éducateur canin propriétaire du créneau horaire.</td>
    </tr>
</table>

*Remarques*

Le système s'assure qu'il n'y ait pas de chevauchement entre les différentes lignes non supprimées de cette table (`time_start` et `time_end`) pour le même parent (`id_weekly_schedule` ou `id_schedule_override`) appartenant au même éducateur canin.
Le système s'assure qu'un créneau horaire appartienne à un calendrier hebdomadaire OU à une exception d'horaire, il ne doit pas appartenir aux deux ni à aucun.

---

La table `absence` contient les informations des vacances des éducateurs canins :

<table>
    <tr>
    	<th  style="text-align:center;" COLSPAN="5">absence</th>
    </tr>
    <tr>
        <th style="text-align:center;">NOM</th>
        <th style="text-align:center;">TYPE</th>
        <th style="text-align:center;">NOT NULL</th>
        <th style="text-align:center;">DESCRIPTION</th>
    </tr>
    <tr>
        <td>date_absence_from</td>
        <td>DATE</td>
        <td style="text-align:center;">X</td>
        <td>La date de début de vacances.</td>
    </tr>
    <tr>
        <td>date_absence_to</td>
        <td>DATE</td>
        <td style="text-align:center;"></td>
        <td>La date de fin de vacances.</td>
    </tr>
    <tr>
        <td>description</td>
        <td>VARCHAR</td>
        <td style="text-align:center;"></td>
        <td>La description de vacances.</td>
    </tr>
        <tr>
        <td>is_deleted</td>
        <td>TINYINT</td>
        <td style="text-align:center;">X</td>
        <td>Le statut des vacances.</td>
    </tr>
    <tr>
        <td>id_educator</td>
        <td>INT</td>
        <td style="text-align:center;">X</td>
        <td>L'identifiant de l'éducateur canin propriétaire des vacances.</td>
    </tr>
</table>

*Remarques*

La valeur du champ `date_absence_to` peut être `null` si le service est suspendu pour un moment.

### Tests unitaires

Afin de tester l'API REST, j'ai utilisé l'outil Postman qui m'a permis d'exécuter des scripts de test pour chaque endpoint de mon API REST. Ces tests sont réalisables en JavaScript en utilisant la bibliothèque proposé par Postman `pm`. Tous les tests unitaires de mon API REST sont identifiables grâce à un code qui leur est propre dans l'annexe [`unit_tests.md`](./unit_tests.md).

#### Format de code

![dateTestPlanningSecondUser](./diagram/drawio/unitTestCodeFormat.svg)

**Définition**

<table>
    <tr>
    	<th style="text-align:center; font-size: 24px;" COLSPAN="2">Code des noms de modèles</th>
    </tr>
    <tr>
        <th>Modèle</th>
        <th>CODE</th>
    </tr>
    <tr>
        <td>User</td>
        <td>USE</td>
    </tr>
    <tr>
        <td>Dog</td>
        <td>DOG</td>
    </tr>
    <tr>
        <td>Document</td>
        <td>Doc</td>
    </tr>
    <tr>
        <td>Absence</td>
        <td>ABS</td>
    </tr>
    <tr>
        <td>WeeklySchedule</td>
        <td>WEE</td>
    </tr>
    <tr>
        <td>ScheduleOverride</td>
        <td>SCH</td>
    </tr>
    <tr>
        <td>TimeSlot</td>
        <td>TIM</td>
    </tr>
    <tr>
        <td>Appoitment</td>
        <td>APP</td>
    </tr>
</table>

---

<table>
    <tr>
    	<th style="text-align:center; font-size: 24px;" COLSPAN="2">Code des actions</th>
    </tr>
    <tr>
        <th>Action</th>
        <th>CODE</th>
    </tr>
    <tr>
        <td>Get all</td>
        <td>GA</td>
    </tr>
    <tr>
        <td>Get one</td>
        <td>GO</td>
    </tr>
    <tr>
        <td>Create one</td>
        <td>CO</td>
    </tr>
    <tr>
        <td>Update one</td>
        <td>UO</td>
    </tr>
    <tr>
        <td>Delete one</td>
        <td>DO</td>
    </tr>
    <tr>
        <td>Connection</td>
        <td>C</td>
    </tr>
    <tr>
        <td>Get user authenticated</td>
        <td>GUA</td>
    </tr>
    <tr>
        <td>Upload dog picture</td>
        <td>UDP</td>
    </tr>
    <tr>
        <td>Download dog picture</td>
        <td>DDP</td>
    </tr>
    <tr>
        <td>Download document</td>
        <td>DD</td>
    </tr>
    <tr>
        <td>Upload note graphical</td>
        <td>UNG</td>
    </tr>
    <tr>
        <td>Download note graphical</td>
        <td>DNG</td>
    </tr>
</table>
#### Syntaxe

Postman propose à ses utilisateurs la possibilité d'écrire des scripts de test pour tester les différentes requêtes de leurs API. Afin de rédiger ces tests, j'ai dû utiliser une certaine syntaxe JavaScript proposée par Postman. Lors de la réalisation de mes tests unitaires avec Postman, j'ai utilisé la fonction `pm.test` afin de tester tous les cas d'utilisation et d'exception de mon API REST. La fonction `pm.test` prend en premier paramètre une chaîne de caractères qui apparaîtra dans la sortie des résultats du test afin d'identifier le test. Le deuxième paramètre est une fonction qui doit retourner un booléen pour indiquer si le test a réussi ou échoué. 

Dans tous mes tests unitaires, j'ai utilisé la fonction `pm.test` avec comme fonction `pm.response.to.have.status(status)` afin de tester si le cas d'utilisation de l'endpoint en question retournait le bon code HTTP. En effet, cette ligne retourne `true` si le code d'état de la réponse est identique à son paramètre de fonction et `false` si ce n'est pas le cas.
Par exemple, afin de tester que l'endpoint de création d'utilisateur `POST api/v1/users` retourne bien le code HTTP `201`, j'ai réalisé le test :

```javascript
pm.test("Right code for successful created ressource", function () {
    pm.response.to.have.status(201);
});
```

Pour les endpoints nécessitant de retourner un message, j'ai également testé que celui-ci soit correct en utilisant la fonction` pm.expect(element1).to.eql(element2)` qui permet de tester que le premier élément corresponde bien au second. 
Par exemple, afin de tester que l'endpoint de création d'utilisateur `POST api/v1/users` avec une adresse e-mail ne respectant pas le bon format retourne bien le code HTTP `400` ainsi que le message d'erreur `Format d'adresse email invalide.`, j'ai réalisé les tests :

```javascript
pm.test("Right code for invalid email format", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for for invalid email format", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Format d'adresse email invalide.");
});
```

Pour les tests unitaires nécessitant le test de plusieurs données de corps de requêtes différentes, j'ai utilisé la fonctionnalité d'importation de CSV proposé par Postman. En effet, Postman permet de sélectionner un fichier CSV contenant différentes données afin de procéder à plusieurs itérations de test avec celles-ci. 
Par exemple, afin de tester que l'endpoint de création de vacances `POST api/v1/absences` avec une date ne respectant pas le bon format retourne bien le code HTTP `400` ainsi que le message d'erreur `Format de date invalide => (YYYY-MM-DD).`, j'ai réalisé les tests :

```javascript
pm.test("Right code for invalid date_to format", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for invalid date_to format", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Format de date invalide => (YYYY-MM-DD).");
});
```

Ce test est réalisé avec les différentes données du fichier CSV disponibles à la fin de l'annexe [`unit_tests.md`](./unit_tests.md).

Pour les tests unitaires nécessitant un test sur l'en-tête d'autorisation, j'ai utilisé la fonction `pm.request.to.have.header(header)`.
Par exemple, afin de tester que l'endpoint de suppression d'un document `DELETE api/v1/documents` avec un api token appartenant à un client retourne bien le code HTTP `403` ainsi que le message d'erreur `Vous n'avez pas les permissions.`, j'ai réalisé les tests :

```javascript
pm.test("Authorization header is present", () => {
  pm.request.to.have.header("Authorization");
});
pm.test("Authorization header is false", function () {
    pm.response.to.have.status(403);
});
pm.test("Right message for access without permission", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Vous n'avez pas les permissions.");
});
```

Pour les tests unitaires retournant une réponse JSON spécifique, j'ai effectué un test afin de vérifier que sa structure soit respectée avec la fonction `pm.response.to.have.jsonSchema(jsonSchema)`.
Par exemple, afin de tester que l'endpoint de récupération d'un chien spécifique `GET api/v1/dogs/{dogId}` avec un api token appartenant à un éducateur canin retourne bien le code HTTP `200` ainsi que la réponse JSON au bon format, j'ai réalisé les tests :

```javascript
pm.test("Authorization header is present", () => {
  pm.request.to.have.header("Authorization");
});
pm.test("Authorization header is right", function () {
    pm.response.to.have.status(200);
});
pm.test("The data structure of the response is correct", () => {
  pm.response.to.have.jsonSchema({
          "type": "object",
          "properties": {
              "id" : {"type" : "integer"},
              "name" : {"type" : "string"},
              "breed" : {"type" : "string"},
              "sex" : {"type" : "string"},
              "picture_serial_id" : {"type" : ["string","null"]},
              "chip_id" : {"type" : ["string","null"]},
              "user_id" : {"type" : "integer"}
          },
          "required": ["id","name","breed","sex","picture_serial_id","chip_id","user_id"]
  })
});
```

### Composer

Composer permet de gérer les dépendances PHP de mon API REST. En premier lieu, Composer permet de générer un fichier nommé `composer.json`. Ce fichier est un moyen pour Composer de rechercher les différentes dépendances que mon projet PHP doit télécharger. Le fichier `composer.json` vérifie également la compatibilité des versions des dépendances de mon projet. C'est-à-dire que si j'utilise un paquet obsolète, Composer me le fera savoir afin d'éviter tout problème. Afin d'installer un paquet comme Dompdf, j'ai dû exécuter la commande suivante dans mon invite de commandes : 

```bash
composer require dompdf/dompdf
```

Après avoir exécuté ce type de commande, mon projet contient maintenant les fichiers `composer.json` et `composer.lock` ainsi que le dossier `vendor`. Comme expliqué auparavant, `composer.json` est comme un guide correspondant aux versions de dépendance que Composer **doit** installer tandis que `composer.lock` est une représentation exact des versions de dépendance qui **ont** été installées. Le dossier `vendor` quant à lui, contient tous les paquets et dépendances installés du projet.

Une fois nos paquets et dépendances installés, il faut maintenant pouvoir les inclure dans un de nos scripts PHP. Pour ce faire, Composer nous facilite la tâche en générant un fichier de chargement automatique nommé `autoload.php`. En incluant ce fichier dans mon fichier `bootstrap.php` qui lui-même étant inclus dans chaque point d'entrées de mon API REST, je peux accéder quand je le souhaite aux différents paquets et dépendance de mon projet. Par exemple, si je souhaite utiliser le paquet Dompdf dans un de mes scripts PHP, je peux écrire la ligne `use Dompdf\Dompdf` afin d'y accéder. 

### Librairies

#### PHPMailer

Installé avec la commande : 

```bash
composer require phpmailer/phpmailer
```

J'ai utilisé PHPMailer dans le contrôleur `HelperController` de la manière suivante :

```php
public static function sendMail(string $message, string $subject,string $emailRecipient, string $attachmentFilePath = null)
{
    // Création d'une instance de la classe PHPMailer en activant les exceptions
    $mail = new PHPMailer(true);
    
    // Chargement du template avec le sujet et le message de l'e-mail
    $body = HelperController::loadMailTemplate($subject,$message);
    try {
        //Paramètres du serveur 
        $mail->SMTPDebug  = SMTP::DEBUG_SERVER;               
        $mail->Host       = getenv('SMTP_HOST');                     
        $mail->SMTPAuth   = true;                                  
        $mail->Username   = getenv('SMTP_USERNAME');                   
        $mail->Password   = getenv('SMTP_PASSWORD');                             
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
        $mail->Port       = 587; 

        //Destinataire
        $mail->setFrom('noreply@douceurdechien.com', 'Douceur de Chien');
        $mail->addAddress($emailRecipient); 

        //Pièce jointe
        if (!is_null($attachmentFilePath)) {
            $mail->addAttachment($attachmentFilePath);
        }
        
        //Contenu
        $mail->isHTML(true);     
        $mail->CharSet = 'UTF-8';      
        $mail->Encoding = 'base64';                      
        $mail->Subject = $subject;
        $mail->AddEmbeddedImage("./../../resources/image/logo_douceur_de_chien.png", "logo-image", "logo_douceur_de_chien.png");
        $mail->Body = $body;
        
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    }
```

[*Documentation de PHPMailer*](https://phpmailer.github.io/PHPMailer/namespaces/default.html)

#### Dompdf

Installé avec la commande : 

```bash
composer require dompdf/dompdf
```

J'ai utilisé Dompdf afin de créer les conditions d'inscription dans le contrôleur `HelperController` de la manière suivante :

```php
public static function storeConditionsRegistration(string $filename,int $package_number,string $date, string $signature_base64,string $userfirstname, string $userlastname)
{
    $dompdf = new DOMPDF();     
    
    ob_start();
    include HelperController::getDefaultDirectory()."resources/template/conditions_registration.php";
    $contents = ob_get_clean();
    
    $dompdf->loadHtml($contents);
    dompdf->render();
    $output = $dompdf->output();
    file_put_contents(HelperController::getDefaultDirectory()."storage/app/conditions_registration/".$filename.".pdf", $output);
}
```

1. Création d'une instance de la classe `DOMPDF`
2. Début de la temporisation de sortie permettant d'inclure les différentes données dans le template HTML et CSS `conditions_registration.php` 
3. Récupération du contenu HTML du tampon de sortie et fermeture de sa session
4. Chargement du contenu HTML avec l'instance `DOMPDF`
5. Transformation du HTML en PDF
6. Récupération du PDF sous forme de données
7. Écriture de ces données dans un fichier PDF stocké sur le serveur

[*Documentation de Dompdf*](https://github.com/dompdf/dompdf/wiki/Usage)

### Endpoints

#### Headers

Les en-têtes que j'ai utilisé dans les différentes points d'entrés de mon API REST sont :

* [`Access-Control-Allow-Origin`](https://developer.mozilla.org/fr/docs/Web/HTTP/Headers/Access-Control-Allow-Origin)
* [`Content-Type`](https://developer.mozilla.org/fr/docs/Web/HTTP/Headers/Content-Type)
* [`Access-Control-Allow-Methods`](https://developer.mozilla.org/fr/docs/Web/HTTP/Headers/Access-Control-Allow-Methods)
* [`Access-Control-Max-Age`](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Max-Age)
* [`Access-Control-Allow-Headers`](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Max-Age)
* [`Content-Length`](https://developer.mozilla.org/fr/docs/Web/HTTP/Headers/Content-Length)

#### Listes des endpoints

* `POST api/v1/users`
* `GET api/v1/users`
* `GET api/v1/users/{idUser}`
* `PATCH api/v1/users/{idUser}`
* `DELETE api/v1/users/{idUser}`
* `POST api/v1/connection`
* `GET api/v1/users/me`
* `GET api/v1/users/educators`

------

* `POST api/v1/dogs`
* `GET api/v1/dogs`
* `GET api/v1/dogs/{idDog}`
* `PATCH api/v1/dogs/{idDog}`
* `DELETE api/v1/dogs/{idDog}`
* `POST api/v1/dogs/uploadPicture`
* `GET api/v1/dogs/downloadPicture/{serial_number}`

------

* `POST api/v1/documents`
* `GET api/v1/documents`
* `GET api/v1/documents/{idDocument}`
* `PATCH api/v1/documents/{idDocument}`
* `DELETE api/v1/documents/{idDocument}`
* `GET api/v1/documents/downloadDocument/{serial_number}`

------

* `POST api/v1/absences`
* `GET api/v1/absences`
* `GET api/v1/absences/{idAbsence}`
* `PATCH api/v1/absences/{idAbsence}`
* `DELETE api/v1/absences/{idAbsence}`

------

* `POST api/v1/weeklySchedules`
* `GET api/v1/weeklySchedules`
* `GET api/v1/weeklySchedules/{idWeeklySchedule}`
* `PATCH api/v1/weeklySchedules/{idWeeklySchedule}`
* `DELETE api/v1/weeklySchedules/{idWeeklySchedule}`

------

* `POST api/v1/scheduleOverrides`
* `GET api/v1/scheduleOverrides`
* `GET api/v1/scheduleOverrides/{idScheduleOverride}`
* `PATCH api/v1/scheduleOverrides/{idScheduleOverride}`
* `DELETE api/v1/scheduleOverrides/{idScheduleOverride}`

------

* `POST api/v1/timeSlots`
* `GET api/v1/timeSlots`
* `GET api/v1/timeSlots/{idTimeSlot}`
* `PATCH api/v1/timeSlots/{idTimeSlot}`
* `DELETE api/v1/timeSlots/{idTimeSlot}`

------

* `POST api/v1/appoitments`
* `GET api/v1/appoitments`
* `GET api/v1/appoitments/{idTimeSlot}`
* `PATCH api/v1/appoitments/{idTimeSlot}`
* `DELETE api/v1/appoitments/{idTimeSlot}`
* `POST api/v1/appoitments/uploadNoteGraphical`
* `POST api/v1/appoitments/downloadNoteGraphical/{serial_number}`
* `GET api/v1/plannings/{idEducator}`

Pour plus d'informations, rendez-vous dans l'annexe [`endpoints.md`](./endpoints.md).

## PWA

### Vuejs

#### Arborescence

[à compléter]

#### Structure

[à compléter]

#### Composants

[à compléter]

#### Librairies

##### Vue Router

[à compléter]

##### Vuex

[à compléter]

##### Axios

[à compléter]

##### Jquery

[à compléter]

##### BootstrapVue

[à compléter]

##### Responsive-Sketchpad

[à compléter]

##### FullCalendar

[à compléter]

##### Webpack

[à compléter]



