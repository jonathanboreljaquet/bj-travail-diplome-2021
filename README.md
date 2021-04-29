Travail de diplôme Douceur de Chien
======

## Description

Travail de diplôme ayant comme objectif la réalisation d'une application WEB pour la société Douceur de Chien. Le but du projet est de faciliter les interactions entre des éducateurs canins et leurs clients. L'application permettra à un client de prendre un rendez-vous autonome respectant le planning d'un éducateur canin et à un éducateur canin de faire signer numériquement les conditions d'utilisation  par exemple.

## Fonctionnalités

### Endpoints de l'API REST
* `POST api/v1/users`
* `GET api/v1/users`
* `GET api/v1/users/{idUser}`
* `PATCH api/v1/users/{idUser}`
* `DELETE api/v1/users/{idUser}`
* `POST api/v1/connection`
* `GET api/v1/users/me`



* `POST api/v1/dogs`
* `GET api/v1/dogs`
* `GET api/v1/dogs/{idDog}`
* `PATCH api/v1/dogs/{idDog}`
* `DELETE api/v1/dogs/{idDog}`
* `POST api/v1/dogs/uploadPicture`
* `GET api/v1/dogs/downloadPicture/{serial_number}`



* `POST api/v1/documents`
* `GET api/v1/documents`
* `GET api/v1/documents/{idDocument}`
* `PATCH api/v1/documents/{idDocument}`
* `DELETE api/v1/documents/{idDocument}`
* `GET api/v1/documents/downloadDocument/{serial_number}`

## Pré-requis

* PHP 7.4
* Composer

## Libraires

### PHP - API REST

* [PHP dotenv](https://github.com/vlucas/phpdotenv)
* [PHPMailer](https://github.com/PHPMailer/PHPMailer)
* [Dompdf](https://github.com/dompdf/dompdf)

### JAVASCRIPT - APPLICATION

### Installation

1. Télécharger le projet
2. Exécuter la commande ``composer install``
3. Copiez le contenu du fichier **.env.example** dans un nouveau fichier nommé **.env**
4. Renseignez les valeurs des variables d'environnement dans le fichier **.env**
5. Exécuter la commande `php dbseed.php` pour avoir les données de base

### Commandes

``composer install`` : Installe toutes les dépendances PHP du projet
`php dbseed.php` : Exécute le script PHP `dbseed.php` afin d'insérer dans la base de données des données de test.