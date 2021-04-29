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
    - [Tests unitaires](#tests-unitaires)
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
      - [GET api/v1/dogs/downloadPicture/{serial_number}](#get-apiv1dogsdownloadpictureserial_number)
        - [Objectif](#objectif-13)
        - [Utilisation concrète](#utilisation-concrète-13)
        - [Flow chart](#flow-chart-13)
        - [Tests unitaires](#tests-unitaires-14)

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

### Headers

### Tests unitaires

Afin de tester l'API REST, j'ai utilisé l'outil Postman qui m'a permis d'exécuter des scripts de test pour chaque endpoint de mon API REST. Ces tests sont réalisables en JavaScript en utilisant la bibliothèque `pm`. Tous les tests unitaires de mon API REST sont identifiables grâce à un code qui leur est propre.

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

### Endpoints

####  POST api/v1/users

##### Objectif

Créer un utilisateur dans la base de données.

##### Utilisation concrète

Cet endpoint permet l'inscription d'un client de deux manières différentes afin que celui-ci puisse accéder aux fonctionnalités de l'application :

* Inscription client de manière autonome
* Inscription client par l'éducateur canin lors d'un appel téléphonique, un e-mail contenant un mot de passe généré aléatoirement est également envoyé par e-mail

Body de la requête :

| Clef        | Définition                              | Obligatoire | Format                            |
| ----------- | --------------------------------------- | :---------: | --------------------------------- |
| email       | L'adresse e-mail de l'utilisateur       |      X      | l'adresse e-mail doit être valide |
| firstname   | Le prénom de l'utilisateur              |      X      |                                   |
| lastname    | Le nom de l'utilisateur                 |      X      |                                   |
| phonenumber | Le numéro de téléphone de l'utilisateur |      X      |                                   |
| address     | L'adresse de l'utilisateur              |      X      |                                   |
| password    | Le mot de passe de l'utilisateur        |             |                                   |

##### Use case

![dateTestPlanningSecondUser](./diagram/UseCaseInscription.png)

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartPostUser.png)

##### Tests unitaires

**[USE_CO1] Create one user without email**

```javascript
pm.test("Right code for invalid attributes", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for request without email", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Attributs invalides.");
});
```

**[USE_CO2] Create one user without firstname**

```javascript
pm.test("Right code for invalid attributes", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for request without firstname", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Attributs invalides.");
});
```

**[USE_CO3] Create one user without lastname**

```javascript
pm.test("Right code for invalid attributes", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for request without lastname", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Attributs invalides.");
});
```

**[USE_CO4] Create one user without phonenumber**

```javascript
pm.test("Right code for invalid attributes", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for request without phone number", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Attributs invalides.");
});
```

**[USE_CO5] Create one user without address**

```javascript
pm.test("Right code for invalid attributes", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for request without address", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Attributs invalides.");
});
```

**[USE_CO6] Create one user with invalid email format**

```javascript
pm.test("Right code for invalid email format", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for for invalid email format", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Format d'adresse email invalide.");
});
```

**[USE_CO7] Create one user without problems**

```javascript
pm.test("Right code for successful created ressource", function () {
    pm.response.to.have.status(201);
});
```

####  GET api/v1/users

##### Objectif

Récupère tous les utilisateurs avec le `code_role` 1 (client) de la base de données.

##### Utilisation concrète

Cet endpoint permet de récupérer tous les clients de l'application. L'endpoint est accessible uniquement par les administrateurs.

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartGetAllUser.png)

##### Tests unitaires

**[USE-GA1] Get all users with a user api token**

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

**[USE-GA2] Get right users with admin api token**

```javascript
pm.test("Authorization header is present", () => {
  pm.request.to.have.header("Authorization");
});
pm.test("Authorization header is right", function () {
    pm.response.to.have.status(200);
});
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

#### GET api/v1/users/{idUser}

##### Objectif

Récupère un utilisateur de la base de données grâce à son identifiant.

##### Utilisation concrète

Cet endpoint permet de récupérer un client spécifique de l'application. L'endpoint est accessible uniquement par les administrateurs.

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartGetOneUser.png)

##### Tests unitaires

**[USE-GO1] Get one user with a user api token**

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

**[USE_GO2] Get one non-existent user**

```javascript
pm.test("User not found", function () {
    pm.response.to.have.status(404);
});
pm.test("Right message for not found response", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Le serveur n'a pas trouvé la ressource demandée.");
});
```

**[USE-GO3] Get right user wtih admin api token**

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
              "email" : {"type" : "string"},
              "firstname" : {"type" : "string"},
              "lastname" : {"type" : "string"},
              "phonenumber" : {"type" : "string"},
              "address" : {"type" : "string"},
              "api_token" : {"type" : "string"},
              "code_role" : {"type" : "integer"},
              "password_hash" : {"type" : ["string","null"]}
          },
          "required": ["id","email","firstname","lastname","phonenumber","address","api_token","code_role","password_hash"]
  })
});
```

####  PATCH api/v1/users/{idUser}

##### Objectif

Modifier un utilisateur dans la base de données.

##### Utilisation concrète

Cet endpoint permet la modification des informations d'un utilisateur. L'endpoint est accessible uniquement par les administrateurs.
Body de la requête :

| Clef        | Définition                              | Obligatoire | Format                            |
| ----------- | --------------------------------------- | :---------: | --------------------------------- |
| email       | L'adresse e-mail de l'utilisateur       |             | l'adresse e-mail doit être valide |
| firstname   | Le prénom de l'utilisateur              |             |                                   |
| lastname    | Le nom de l'utilisateur                 |             |                                   |
| phonenumber | Le numéro de téléphone de l'utilisateur |             |                                   |
| address     | L'adresse de l'utilisateur              |             |                                   |

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartUpdateOneUser.png)

##### Tests unitaires

**[USE_UO1] Update one user with a user api token**

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

**[USE_UO2] Update one non-existent user**

```javascript
pm.test("User not found", function () {
    pm.response.to.have.status(404);
});
pm.test("Right message for not found response", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Le serveur n'a pas trouvé la ressource demandée.");
});
```

**[USE_UO3] Update one user with invalid email format**

```javascript
pm.test("Right code for invalid email format", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for for invalid email format", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Format d'adresse email invalide.");
});
```

**[USE_UO4] Update one user without problems**

```javascript
pm.test("Right code for successful updated ressource", function () {
    pm.response.to.have.status(200);
});
```

####  DELETE api/v1/users/{idUser}

##### Objectif

Supprimer un utilisateur dans la base de données.

##### Utilisation concrète

Cet endpoint permet la suppression définitive d'un utilisateur. L'endpoint est accessible uniquement par les administrateurs.

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartDeleteOneUser.png)

##### Tests unitaires

**[USE-DO1] Delete one user with a user api token**

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

**[USE_DO2] Delete one non-existent user**

```javascript
pm.test("User not found", function () {
    pm.response.to.have.status(404);
});
pm.test("Right message for not found response", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Le serveur n'a pas trouvé la ressource demandée.");
});
```

**[USE-DO3] Delete one user without problems**

```javascript
pm.test("Right code for successful deleted ressource", function () {
    pm.response.to.have.status(200);
});
```

####  GET api/v1/users/me

##### Objectif

Récupérer toutes les informations de l'utilisateur authentifié grâce à son api token dans le header `Authorization`.

##### Utilisation concrète

Cet endpoint permet de récupérer toutes les données de l'utilisateur authentifié (chiens, documents, rendez-vous).

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartGetUserAuthdrawio.png)

##### Tests unitaires

**[USE-GUA1] Get all user information of the current logged in non-existent user**

```javascript
pm.test("User not found", function () {
    pm.response.to.have.status(404);
});
pm.test("Right message for not found response", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Le serveur n'a pas trouvé la ressource demandée.");
});
```

**[USE-GUA2] Get all user information of the current logged in user**

```javascript
pm.test("Authorization header is present", () => {
  pm.request.to.have.header("Authorization");
});

pm.test("The data structure of the response is correct", () => {
  pm.response.to.have.jsonSchema({
          "type": "object",
          "properties": {
              "id" : {"type" : "integer"},
              "email" : {"type" : "string"},
              "firstname" : {"type" : "string"},
              "lastname" : {"type" : "string"},
              "phonenumber" : {"type" : "string"},
              "address" : {"type" : "string"},
              "api_token" : {"type" : "string"},
              "code_role" : {"type" : "integer"},
              "password_hash" : {"type" : "null"},
              "dogs" : {
                  "type" : "array",
                  "properties" : {
                      "id" : {"type" : "integer"},
                      "name" : {"type" : "string"},
                      "breed" : {"type" : "string"},
                      "sex" : {"type" : "string"},
                      "picture_seral_number" : {"type" : "string"},
                      "chip_id" : {"type" : "integer"},
                      "user_id" : {"type" : "integer"},
                  },
                  "required": ["id","name","breed","sex","picture_seral_number","chip_id","user_id"]
              },
              "documents" : {
                  "type" : "array",
                  "properties" : {
                      "id" : {"type" : "integer"},
                      "document_serial_number" : {"type" : "string"},
                      "type" : {"type" : "string"},
                      "user_id" : {"type" : "string"},
                      "package_number" : {"type" : "null"},
                      "signature_base64" : {"type" : "null"},
                  },
                  "required": ["id","document_serial_number","type","user_id"]
              }
          },
          "required": ["id","email","firstname","lastname","phonenumber","address","api_token","code_role","password_hash","dogs"]
  })
});
```

####  POST api/v1/connection

##### Objectif

Récupérer l'api token d'un utilisateur dans la base de données grâce à son adresse e-mail ainsi que son mot de passe .

##### Utilisation concrète

Cet endpoint permet la récupération de l'api token afin de permettre à l'utilisateur d'accéder aux différentes fonctionnalités de l'application.Body de la requête :

| Clef     | Définition                        | Obligatoire | Format |
| -------- | --------------------------------- | :---------: | ------ |
| email    | L'adresse e-mail de l'utilisateur |      X      |        |
| password | Le prénom de l'utilisateur        |      X      |        |

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartConnection.png)

##### Tests unitaires

**[USE-C1] Connect user without email**

```javascript
pm.test("Right code for invalid attributes", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for request without email", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Attributs invalides.");
});
```

**[USE-C2] Connect user with an unrecognized email**

```javascript
pm.test("Right code for invalid login", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for invalid login", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Identifiants de connexion invalides.");
});
```

**[USE-C3] Connect user with wrong password**

```javascript
pm.test("Right code for invalid login", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for invalid login", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Identifiants de connexion invalides.");
});
```

**[USE-C4] Successful connection**

```javascript
pm.test("The data structure of the response is correct", () => {
  pm.response.to.have.jsonSchema({
          "type": "object",
          "properties": {
              "api_token" : {"type" : "string"}
          },
          "required": ["api_token"]
  })
});
```

####  POST api/v1/dogs

##### Objectif

Créer un chien pour un utilisateur dans la base de données.

##### Utilisation concrète

Cet endpoint permet d'ajouter un chien à un utilisateur. L'endpoint est accessible uniquement par les administrateurs.
Body de la requête :

| Clef                  | Définition                                           | Obligatoire | Format |
| --------------------- | ---------------------------------------------------- | :---------: | ------ |
| name                  | Le nom du chien                                      |      X      |        |
| breed                 | La race du chien                                     |      X      |        |
| sex                   | Le sexe du chien                                     |      X      |        |
| picture_serial_number | Le numéro de série de la photo du chien              |             |        |
| chip_id               | Le numéro de puce sous-cutanée du chien              |             |        |
| user_id               | L'identifiant de l'utilisateur propriétaire du chien |      X      |        |

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartPostDog.png)

##### Tests unitaires

**[DOG_CO1] Create one dog with a user api token**

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

**[DOG_CO2] Create one dog without name**

```javascript
pm.test("Right code for invalid attributes", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for request without name", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Attributs invalides.");
});
```

**[DOG_CO3] Create one dog without breed**

```javascript
pm.test("Right code for invalid attributes", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for request without breed", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Attributs invalides.");
});
```

**[DOG_CO4] Create one dog without sex**

```javascript
pm.test("Right code for invalid attributes", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for request without sex", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Attributs invalides.");
});
```

**[DOG_CO5] Create one dog without user_id**

```javascript
pm.test("Right code for invalid attributes", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for request without user_id", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Attributs invalides.");
});
```

**[DOG_CO6] Create one dog for non-existent user**

```javascript
pm.test("User not found", function () {
    pm.response.to.have.status(404);
});
pm.test("Right message for not found response", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Le serveur n'a pas trouvé la ressource demandée.");
});
```

**[DOG_CO7] Create one dog without problems**

```javascript
pm.test("Right code for successful created ressource", function () {
    pm.response.to.have.status(201);
});
```

####  GET api/v1/dogs

##### Objectif

Récupère tous les chiens de la base de données.

##### Utilisation concrète

Cet endpoint permet de récupérer tous les chiens existants de l'application. L'endpoint est accessible uniquement par les administrateurs.

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartGetAllDog.png)

##### Tests unitaires

**[DOG-GA1] Get all dogs with a user api token**

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

**[DOG-GA2] Get right dogs with admin api token**

```javascript
pm.test("Authorization header is present", () => {
  pm.request.to.have.header("Authorization");
});
pm.test("Authorization header is right", function () {
    pm.response.to.have.status(200);
});
pm.test("The data structure of the response is correct", () => {
  pm.response.to.have.jsonSchema({
      "type": "array",
      "items": [{
          "type": "object",
          "properties": {
              "id" : {"type" : "integer"},
              "name" : {"type" : "string"},
              "breed" : {"type" : "string"},
              "sex" : {"type" : "string"},
              "picture_serial_number" : {"type" : ["string","null"]},
              "chip_id" : {"type" : "string"},
              "user_id" : {"type" : "integer"}
          },
          "required": ["id","name","breed","sex","picture_serial_number","chip_id","user_id"]
      }]
  })
});
```

#### GET api/v1/dogs/{idDog}

##### Objectif

Récupère un chien de la base de données grâce à son identifiant.

##### Utilisation concrète

Cet endpoint permet de récupérer un chien spécifique de l'application. L'endpoint est accessible uniquement par les administrateurs.

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartGetOneDog.png)

##### Tests unitaires

**[DOG-GO1] Get one dog with a user api token**

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

**[DOG_GO2] Get one non-existent dog**

```javascript
pm.test("Dog not found", function () {
    pm.response.to.have.status(404);
});
pm.test("Right message for not found response", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Le serveur n'a pas trouvé la ressource demandée.");
});
```

**[DOG-GO3] Get right dog with admin api token**

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
              "picture_serial_number" : {"type" : ["string","null"]},
              "chip_id" : {"type" : ["string","null"]},
              "user_id" : {"type" : "integer"}
          },
          "required": ["id","name","breed","sex","picture_serial_number","chip_id","user_id"]
  })
});
```

####  PATCH api/v1/dogs/{idDog}

##### Objectif

Modifier un chien dans la base de données.

##### Utilisation concrète

Cet endpoint permet la modification des informations d'un chien. L'endpoint est accessible uniquement par les administrateurs.
Body de la requête :

| Clef                  | Définition                              | Obligatoire | Format |
| --------------------- | --------------------------------------- | :---------: | ------ |
| name                  | Le nom du chien                         |             |        |
| breed                 | La race du chien                        |             |        |
| sex                   | Le sexe du chien                        |             |        |
| picture_serial_number | Le numéro de série de la photo du chien |             |        |
| chip_id               | Le numéro de puce sous-cutanée du chien |             |        |

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartUpdateOneDog.png)

##### Tests unitaires

**[DOG_UO1] Update one dog with a user api token**

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

**[DOG_UO2] Update one non-existent dog**

```javascript
pm.test("Dog not found", function () {
    pm.response.to.have.status(404);
});
pm.test("Right message for not found response", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Le serveur n'a pas trouvé la ressource demandée.");
});
```

**[DOG_UO3] Update one dog without problems**

```javascript
pm.test("Right code for successful updated ressource", function () {
    pm.response.to.have.status(200);
});
```

####  DELETE api/v1/dogs/{idDog}

##### Objectif

Supprimer un chien dans la base de données.

##### Utilisation concrète

Cet endpoint permet la suppression définitive d'un chien. L'endpoint est accessible uniquement par les administrateurs.

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartDeleteOneDog.png)

##### Tests unitaires

**[DOG-DO1] Delete one dog with a user api token**

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

**[DOG_DO2] Delete one non-existent dog**

```javascript
pm.test("Dog not found", function () {
    pm.response.to.have.status(404);
});
pm.test("Right message for not found response", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Le serveur n'a pas trouvé la ressource demandée.");
});
```

**[DOG-DO3] Delete one dog without problems**

```javascript
pm.test("Right code for successful deleted ressource", function () {
    pm.response.to.have.status(200);
});
```

####  POST api/v1/dogs/uploadPicture

##### Objectif

Upload une image sur le serveur et l'attribue à un chien de la base de données.

##### Utilisation concrète

Cet endpoint permet d'ajouter une photo à un chien. L'endpoint est accessible uniquement par les administrateurs.
Body de la requête :

| Clef        | Définition             | Obligatoire | Format      |
| ----------- | ---------------------- | :---------: | ----------- |
| dog_picture | La photo du chien      |      X      | PNG ou JPEG |
| dog_id      | L'identifiant du chien |      X      |             |

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartUploadDogPicture.png)

##### Tests unitaires

**[DOG-UDP1] Upload dog picture with a user api token**

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

**[DOG_UDP2] Upload dog picture without dog_picture**

```javascript
pm.test("Right code for invalid attributes", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for request without dog_picture", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Attributs invalides.");
});
```

**[DOG_UDP3] Upload dog picture without dog_id**

```javascript
pm.test("Right code for invalid attributes", function () {
    pm.response.to.have.status(400);
});
pm.test("Right message for request without dog_id", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Attributs invalides.");
});
```

**[DOG_UDP4] Upload dog picture for non-existant dog**

```javascript
pm.test("Dog not found", function () {
    pm.response.to.have.status(404);
});

pm.test("Right message for not found response", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Le serveur n'a pas trouvé la ressource demandée.");
});
```

**[DOG_UDP5] Upload dog picture with invalid image type format**

```javascript
pm.test("Right code for invalid image file format", function () {
    pm.response.to.have.status(415);
});
pm.test("Right message for invalid image file format", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Format d'image par pris en charge.");
});
```

**[DOG-UDP6] Upload dog picture without problems**

```javascript
pm.test("Right code for successful uploaded ressource", function () {
    pm.response.to.have.status(200);
});
```

####  GET api/v1/dogs/downloadPicture/{serial_number}

##### Objectif

Récupérer l'image d'un chien stockée dans le serveur.

##### Utilisation concrète

Cet endpoint permet de récupérer la photo d'un chien en base64 grâce à son identificateur.

##### Flow chart

![dateTestPlanningSecondUser](./diagram/drawio/flowchartDownloadDogPicture.png)

##### Tests unitaires

**[DOG-DDP1] Download non-existant dog picture**

```javascript
pm.test("Dog not found", function () {
    pm.response.to.have.status(404);
});
pm.test("Right message for not found response", function () {
    const responseJson = pm.response.json();
    pm.expect(responseJson.error).to.eql("Le serveur n'a pas trouvé la ressource demandée.");
});
```

**[DOG-DDP2] Download dog picture without problems**

```javascript
pm.test("The server returns some information", () => {
    pm.expect(pm.response.body);
});
```
