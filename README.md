# RealStore

RealStore est un projet e-commerce Laravel 12 orienté boutique en ligne, panier client, commande et gestion du stock.

> Notice de documentation : ce dépôt évolue en continu. À chaque changement fonctionnel, technique ou de procédure, il faut mettre à jour le README et les fichiers Markdown concernés pour refléter l’état réel du projet.

## Vue d’ensemble

- Gestion des produits et catégories
- Panier client avec validation de stock
- Commandes avec statut et stock réajusté
- Authentification client/admin
- CI/CD avec GitHub Actions
- Docker local pour un démarrage isolé

## Stack

- PHP 8.3
- Laravel 12
- SQLite en mémoire pour les tests
- MySQL pour le runtime local via Docker
- Vite + Tailwind
- PHPUnit

## Prérequis

- PHP 8.3+
- Composer
- Node.js 20+
- MySQL 8 ou SQLite pour les tests

## Installation depuis zéro

```bash
git clone <url-du-repo>
cd realstore
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

## Commandes courantes

```bash
# Tests
php artisan test

# Vérifier le style PHP
./vendor/bin/pint --test

# Corriger le style PHP
./vendor/bin/pint

# Construire les assets front
npm run build
```

## Tests et qualité

Le dépôt suit les règles suivantes :

- chaque fonctionnalité métier doit avoir son test
- les tests doivent utiliser SQLite mémoire et `RefreshDatabase`
- le code PHP doit rester propre via `pint`
- l’exécution complète de la suite est obligatoire avant validation

## Architecture principale

- `app/Services/CartService.php` : gestion du panier et contrôle du stock
- `app/Services/OrderService.php` : checkout, annulation, statut, décrement/increment stock
- `app/Models/` : modèles Eloquent
- `resources/js/cart.js` : logique client du panier
- `tests/Unit/Services/` : tests unitaires des services
- `.github/workflows/ci.yml` : pipeline CI
- `docker-compose.yml` : environnement local

## CI/CD

Le workflow GitHub Actions exécute au minimum :

- `composer install`
- `npm ci`
- `./vendor/bin/pint --test`
- `php artisan test`

## Docker

Le projet inclut un environnement Docker local :

```bash
docker compose up --build
```

L'application est ensuite disponible à l'adresse `http://localhost:8000`.

## Règles de contribution

- un changement fonctionnel = un commit
- un correctif ou ajout métier doit être accompagné de son test
- les fichiers Markdown du dépôt doivent être maintenus à jour avec l’état réel du projet
- toute modification doit être validée par `./vendor/bin/pint` et `php artisan test`

## État actuel

Le projet couvre actuellement :

- gestion du panier et du stock
- test unitaire du service de panier
- test unitaire du service de commande
- CI GitHub Actions
- environnement Docker local
- documentation projet de démarrage

