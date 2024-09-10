# Backend API pour Gestion de Finances Personnelles

## Introduction

Ce projet est le backend d'une application de gestion de finances personnelles développée avec Laravel. Il gère l'authentification des utilisateurs et les transactions financières (ajout, affichage, modification, suppression). L'API est documentée avec Swagger pour faciliter l'intégration avec le front-end et tester les endpoints de l'API.

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- **PHP** >= 8.0
- **Composer** pour la gestion des dépendances PHP
- **Laravel** >= 9.x
- **MySQL** ou autre base de données compatible
- **Swagger** pour la documentation et les tests de l'API

## Installation

### Cloner le dépôt

Clonez le dépôt GitHub avec la commande suivante :

```bash
git clone https://github.com/your-repository-url.git
cd your-repository-folder
```

### Installer les dépendances
Installez les dépendances PHP avec Composer :

```bash
composer install
```

### Configurer l'environnement
Renommez le fichier ```.env.example``` en ```.env``` et mettez à jour les variables d'environnement avec vos informations de base de données et autres configurations :

```bash
cp .env.example .env
```

Éditez le fichier .env pour inclure les informations suivantes :
```
# Configuration de la base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Configuration de Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost
```
### Générer la clé de l'application
Générez la clé de l'application Laravel avec :

```bash
php artisan key:generate
```

### Migrer la base de données
Appliquez les migrations pour créer les tables nécessaires dans la base de données :

```bash
php artisan migrate:fresh --seed
```

## Configuration de Swagger
Swagger est utilisé pour documenter et tester l'API. Voici comment configurer et utiliser Swagger avec Laravel.

### Installer Swagger
Nous utilisons le package darkaonline/l5-swagger pour intégrer Swagger à Laravel. Installez-le via Composer :

```bash
composer require darkaonline/l5-swagger
```

### Publier les configurations
Publiez les fichiers de configuration Swagger avec la commande suivante :

```bash
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

### Configurer Swagger
Modifiez le fichier de configuration config/l5-swagger.php pour ajuster les paramètres selon vos besoins. Assurez-vous que les chemins d'accès et les options de sécurité sont correctement configurés.

### Générer la documentation Swagger
Générez la documentation Swagger en exécutant la commande suivante :

```bash
php artisan l5-swagger:generate
```

La documentation sera disponible à l'URL suivante : [your-app-url/api/](http://your-app-url/api/)documentation.

## Authentification via Swagger
Pour tester les endpoints protégés par authentification dans Swagger, suivez ces étapes :

### Obtenez un token JWT
1. Allez à l'endpoint /api/v1/login dans Swagger.
2. Fournissez les informations de connexion (email et mot de passe).
3. Exécutez la requête pour obtenir un token JWT.

### Utilisez le token pour l'authentification
1. Une fois que vous avez le token, cliquez sur le bouton "Authorize" dans l'interface Swagger.
2. Entrez le token JWT dans le champ approprié (préfixé par Bearer).
3. Cliquez sur "Authorize" pour appliquer le token à toutes les requêtes suivantes.

## Endpoints API
Voici une liste des endpoints disponibles dans l'API :

### Authentication
- ```POST /api/v1/login``` - Connexion de d'un utilisateur et récupération du token

### Utilisateurs
- ```GET /api/v1/users``` - Liste des utilisateurs
- ```POST /api/v1/users``` - Créer un nouvel utilisateur
- ```GET /api/v1/users/{id}``` - Détails d'un utilisateur
- ```PUT /api/v1/users/{id}``` - Mettre à jour un utilisateur
- ```DELETE /api/v1/users/{id}``` - Supprimer un utilisateur

### Transactions
- ```GET /api/v1/transactions``` - Lister les transactions de l'utilisateur authentifié
- ```POST /api/v1/transactions``` - Ajouter une transaction
- ```GET /api/v1/transactions/{id}``` - Détails d'une transaction
- ```PUT /api/v1/transactions/{id}``` - Mettre à jour une transaction
- ```DELETE /api/v1/transactions/{id}``` - Supprimer une transaction

### Catégories
- ```GET /api/v1/categories``` - Lister les categories de l'utilisateur authentifié
- ```POST /api/v1/categories``` - Ajouter une categorie
- ```GET /api/v1/categories/{id}``` - Détails d'une categorie
- ```PUT /api/v1/categories/{id}``` - Mettre à jour une categorie
- ```DELETE /api/v1/categories/{id}``` - Supprimer une categorie

## Contribuer
Pour contribuer à ce projet :
1. Forkez le dépôt.
2. Créez une branche pour votre fonctionnalité ou correctif.
3. Soumettez une Pull Request (PR) avec une description détaillée des modifications.

## Support
Pour toute question ou problème, veuillez ouvrir une issue sur [GitHub Issues](https://github.com/features/issues).