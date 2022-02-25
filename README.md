Travail de diplôme Douceur de Chien
======

## Description

Travail de diplôme ayant comme objectif la réalisation d'une application WEB pour la société Douceur de Chien. Le but du projet est de faciliter les interactions entre des éducateurs canins et leurs clients. L'application permettra à un client de prendre un rendez-vous autonome respectant le planning d'un éducateur canin et à un éducateur canin de faire signer numériquement les conditions d'utilisation par exemple.

## Documentation 

* [Documentation technique](./documentation/documentation_technique.md)
* [Rapport](./rendu/rapport.pdf)
* [Manuel utilisateur](./rendu/manuel_utilisateur.pdf)
* [Journal de bord](./documentation/logbook.md)
* [Tests unitaires de l'API](./rendu/postman_unit_tests.md)

## Fonctionnalités

### Endpoints de l'API REST

La documentation des endpoints est disponible dans un [annexe](./documentation/endpoints.md).

### Fonctionnalités de l'application WEB

**Invité**

* Consultation des informations de la société
* Consultation des créneaux horaires disponibles des éducateurs canins de la société
* Inscription
* Connexion

**(Utilisateur authentifié) Client**

* Consultation des informations personnelles
* Modification du mot de passe
* Téléchargement de document
* Consultation des contenus séances
* Prise de rendez-vous autonome
* Déconnexion

**(Utilisateur authentifié) Éducateur canin**

* Consultation de tous les clients de la société
* Ajout d'un client
* Création, édition et suppression des informations personnelles des clients
* Création, édition et suppression des informations des chiens des clients
* Création et suppression des conditions d'inscription signées numériquement des clients
* Ajout et suppression des documents PDF d'informations des clients
* Modification de photo de chien depuis caméra ou fichier existant
* Consultation des rendez-vous à venir
* Prise de rendez-vous avec un client
* Création et édition des contenus séances
* Prise de notes graphiques concernant un rendez-vous
* Déconnexion

## Pré-requis

* PHP 7.4
* Composer
* npm

## Librairies

### PHP - API REST

* [PHP dotenv](https://github.com/vlucas/phpdotenv)
* [PHPMailer](https://github.com/PHPMailer/PHPMailer)
* [Dompdf](https://github.com/dompdf/dompdf)

### VUE.JS - APPLICATION

* [BootstrapVue](https://bootstrap-vue.org/)
* [Vue router](https://router.vuejs.org/)
* [Vuex](https://vuex.vuejs.org/)
* [Axios](https://github.com/axios/axios)
* [JQuery](https://jquery.com/)
* [Moment.js](https://momentjs.com/)
* [Alertify](https://alertifyjs.com/) avec le composant Vue développé par la communauté [vue-alertify](https://github.com/sj82516/vue-alertify)
* [Recaptcha](https://www.google.com/recaptcha/about/) avec le composant Vue développé par la communauté [vue-recaptcha](https://www.npmjs.com/package/vue-recaptcha)
* [Signature Pad](https://github.com/szimek/signature_pad) avec le composant Vue développé par la communauté [vue-signature-pad](https://github.com/neighborhood999/vue-signature-pad#readme)

## Installation

### PHP - API REST

1. Télécharger le projet
2. Déplacer le dossier `api_rest` à la racine de votre serveur HTTP
3. Accéder au répertoire `api_rest` en ligne de commande
4. Exécuter la commande `composer install`
5. Copiez le contenu du fichier **.env.example** dans un nouveau fichier nommé **.env**
6. Renseignez les valeurs des variables d'environnement dans le fichier **.env**
7. Exécuter la commande `php dbseed.php` pour générer les données de test

### JAVASCRIPT - APPLICATION

1. Télécharger le projet
2. Accéder au répertoire `pwa` en ligne de commande
3. Exécuter la commande `npm install`
4. Exécuter la commande `npm run serve`
5. Ouvrir l'url `http://localhost:8080/`

## Commandes

* `composer install` : Installe toutes les dépendances PHP du projet
* `php dbseed.php` : Exécute le script PHP `dbseed.php` afin d'insérer dans la base de données des données de test.
* `npm install` : Installe toutes les dépendances Javascript
* `npm run serve` : Démarre un serveur de développement basé sur [webpack-dev-server](https://github.com/webpack/webpack-dev-server)

