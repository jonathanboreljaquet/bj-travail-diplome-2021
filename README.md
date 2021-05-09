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
* `GET api/v1/users/educators`

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

* `POST api/v1/absences`
* `GET api/v1/absences`
* `GET api/v1/absences/{idAbsence}`
* `PATCH api/v1/absences/{idAbsence}`
* `DELETE api/v1/absences/{idAbsence}`

* `POST api/v1/weeklySchedules`
* `GET api/v1/weeklySchedules`
* `GET api/v1/weeklySchedules/{idWeeklySchedule}`
* `PATCH api/v1/weeklySchedules/{idWeeklySchedule}`
* `DELETE api/v1/weeklySchedules/{idWeeklySchedule}`

* `POST api/v1/scheduleOverrides`
* `GET api/v1/scheduleOverrides`
* `GET api/v1/scheduleOverrides/{idScheduleOverride}`
* `PATCH api/v1/scheduleOverrides/{idScheduleOverride}`
* `DELETE api/v1/scheduleOverrides/{idScheduleOverride}`

* `POST api/v1/timeSlots`
* `GET api/v1/timeSlots`
* `GET api/v1/timeSlots/{idTimeSlot}`
* `PATCH api/v1/timeSlots/{idTimeSlot}`
* `DELETE api/v1/timeSlots/{idTimeSlot}`

* `POST api/v1/appoitments`
* `GET api/v1/appoitments`
* `GET api/v1/appoitments/{idTimeSlot}`
* `PATCH api/v1/appoitments/{idTimeSlot}`
* `DELETE api/v1/appoitments/{idTimeSlot}`
* `POST api/v1/appoitments/uploadNoteGraphical`
* `POST api/v1/appoitments/downloadNoteGraphical/{serial_number}`

* `GET api/v1/plannings/{idEducator}`

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
5. Exécuter la commande `php dbseed.php` pour générer les données de test

### Commandes

``composer install`` : Installe toutes les dépendances PHP du projet
`php dbseed.php` : Exécute le script PHP `dbseed.php` afin d'insérer dans la base de données des données de test.