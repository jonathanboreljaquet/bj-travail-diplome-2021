# Travail de diplôme - Documentation technique

- [Travail de diplôme - Documentation technique](#travail-de-diplôme---documentation-technique)
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
    - [Headers](#headers)
    - [Tests unitaires](#tests-unitaires)
    - [Librairies](#librairies)
      - [PHPMailer](#phpmailer)
      - [Dompdf](#dompdf)
    - [Endpoints](#endpoints)
      - [POST api/v1/users](#post-apiv1users)
        - [Objectif](#objectif)
        - [Utilisation concrète](#utilisation-concrète)
        - [Use case](#use-case)
        - [Flow chart](#flow-chart)
        - [Tests unitaires](#tests-unitaires-1)
      - [GET api/v1/users](#get-apiv1users)
        - [Objectif](#objectif-1)
        - [Utilisation concrète](#utilisation-concrète-1)
        - [Flow chart](#flow-chart-1)
        - [Tests unitaires](#tests-unitaires-2)
      - [GET api/v1/users/{idUser}](#get-apiv1usersiduser)
        - [Objectif](#objectif-2)
        - [Utilisation concrète](#utilisation-concrète-2)
        - [Flow chart](#flow-chart-2)
        - [Tests unitaires](#tests-unitaires-3)
      - [PATCH api/v1/users/{idUser}](#patch-apiv1usersiduser)
        - [Objectif](#objectif-3)
        - [Utilisation concrète](#utilisation-concrète-3)
        - [Flow chart](#flow-chart-3)
        - [Tests unitaires](#tests-unitaires-4)
      - [DELETE api/v1/users/{idUser}](#delete-apiv1usersiduser)
        - [Objectif](#objectif-4)
        - [Utilisation concrète](#utilisation-concrète-4)
        - [Flow chart](#flow-chart-4)
        - [Tests unitaires](#tests-unitaires-5)
      - [GET api/v1/users/me](#get-apiv1usersme)
        - [Objectif](#objectif-5)
        - [Utilisation concrète](#utilisation-concrète-5)
        - [Flow chart](#flow-chart-5)
        - [Tests unitaires](#tests-unitaires-6)
      - [POST api/v1/connection](#post-apiv1connection)
        - [Objectif](#objectif-6)
        - [Utilisation concrète](#utilisation-concrète-6)
        - [Flow chart](#flow-chart-6)
        - [Tests unitaires](#tests-unitaires-7)
      - [POST api/v1/dogs](#post-apiv1dogs)
        - [Objectif](#objectif-7)
        - [Utilisation concrète](#utilisation-concrète-7)
        - [Flow chart](#flow-chart-7)
        - [Tests unitaires](#tests-unitaires-8)
      - [GET api/v1/dogs](#get-apiv1dogs)
        - [Objectif](#objectif-8)
        - [Utilisation concrète](#utilisation-concrète-8)
        - [Flow chart](#flow-chart-8)
        - [Tests unitaires](#tests-unitaires-9)
      - [GET api/v1/dogs/{idDog}](#get-apiv1dogsiddog)
        - [Objectif](#objectif-9)
        - [Utilisation concrète](#utilisation-concrète-9)
        - [Flow chart](#flow-chart-9)
        - [Tests unitaires](#tests-unitaires-10)
      - [PATCH api/v1/dogs/{idDog}](#patch-apiv1dogsiddog)
        - [Objectif](#objectif-10)
        - [Utilisation concrète](#utilisation-concrète-10)
        - [Flow chart](#flow-chart-10)
        - [Tests unitaires](#tests-unitaires-11)
      - [DELETE api/v1/dogs/{idDog}](#delete-apiv1dogsiddog)
        - [Objectif](#objectif-11)
        - [Utilisation concrète](#utilisation-concrète-11)
        - [Flow chart](#flow-chart-11)
        - [Tests unitaires](#tests-unitaires-12)
      - [POST api/v1/dogs/uploadPicture](#post-apiv1dogsuploadpicture)
        - [Objectif](#objectif-12)
        - [Utilisation concrète](#utilisation-concrète-12)
        - [Flow chart](#flow-chart-12)
        - [Tests unitaires](#tests-unitaires-13)
      - [GET api/v1/dogs/downloadPicture/{serial_id}](#get-apiv1dogsdownloadpictureserial_id)
        - [Objectif](#objectif-13)
        - [Utilisation concrète](#utilisation-concrète-13)
        - [Flow chart](#flow-chart-13)
        - [Tests unitaires](#tests-unitaires-14)
      - [POST api/v1/documents](#post-apiv1documents)
        - [Objectif](#objectif-14)
        - [Utilisation concrète](#utilisation-concrète-14)
        - [Flow chart](#flow-chart-14)
        - [Tests unitaires](#tests-unitaires-15)
  - [PWA](#pwa)
    - [Arborescence](#arborescence-1)
    - [Librairies](#librairies-1)
      - [Bootstrap](#bootstrap)
      - [Responsive-Scketchpad](#responsive-scketchpad)
      - [FullCalendar](#fullcalendar)

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

Le dossier Controllers contient les contrôleurs de l'API REST, comme leur nom l'indique. Le but des contrôleurs est de contrôler les différents cas d'utilisation et d'autorisations d'accès en utilisant, s'il le faut, les DAO afin de communiquer avec la base de données et en retournant les différents codes HTTP et messages en format JSON. 
Exemple de la classe `DogController` :

![dateTestPlanningSecondUser](./diagram/umletino/dogController.png)

Dans ce dossier réside également les contrôleurs `ResponseController` et `HelperController`. Le `ResponseController` permet de retourner toutes les différentes réponses HTTP. Le `HelperController` permet l'utilisation de méthode dite d'aide et qui n'aurait pas leur place dans un contrôleur basique.
Classes `ResponseController` et `HelperController` :

![dateTestPlanningSecondUser](./diagram/umletino/ResponseHelperController.png)

#### app/System

Le dossier System contient la classe `DatabaseConnector` qui permet la connexion à la base de données en récupérant les variables d'environnements *PHP dotenv* et la classe `Constants` permettant de stocker les différentes constantes de l'API REST.
Classes `DatabaseConnector` et `Constants` :

![dateTestPlanningSecondUser](./diagram/umletino/databaseConnectorConstants.png)

#### public

Le dossier public contient les différents fichiers d'entrées de l'API REST. Les fichiers d'entrées récupère le `verb HTTP` d'une requête HTTP afin de pouvoir exécuter les bonnes méthodes des contrôleurs. Ces fichiers s'occupent également d'attribuer les headers et le body si nécessaire. 

#### storage

Dossier contenant les différents fichiers uploadés de l'API REST, comme les documents PDF ou les photos de chien par exemple. 

#### bootstrap.php

Fichier de bootage de l'API REST inclus dans tous les fichiers d'entrées, celui-ci permet de : 

* Charger les dépendances PHP dans le dossier vendor
* Charger les variables d'environnements
* Créer la connexion à la base de données

### Structure

![dateTestPlanningSecondUser](./diagram/drawio/system.png)

### Base de données

### Headers

### Tests unitaires

Afin de tester l'API REST, j'ai utilisé l'outil Postman qui m'a permis d'exécuter des scripts de test pour chaque endpoint de mon API REST. Ces tests sont réalisables en JavaScript en utilisant la bibliothèque `pm`. Tous les tests unitaires de mon API REST sont identifiables grâce à un code qui leur est propre.  cf. annexe `unit_tests.md`

**Format de code**

![dateTestPlanningSecondUser](./diagram/drawio/unitTestCodeFormat.png)

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
</table>

### Librairies

#### PHPMailer

#### Dompdf

### Endpoints

Les endpoints sont tous documentés dans l'annexe `endpoints.md`.

## PWA

### Arborescence

### Structure

#### Méthodes

### Librairies

#### Bootstrap

#### Responsive-Scketchpad

#### FullCalendar

