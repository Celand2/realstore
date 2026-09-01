# Directives du projet RealStore

> Notice de documentation : les fichiers Markdown du dépôt doivent refléter l’état réel du projet. À chaque changement fonctionnel, technique ou de procédure, ce fichier et les autres documents concernés doivent être mis à jour.

## Objectif Prioritaire : Conformité & Valeur du Dépôt
Avant d'ajouter toute nouvelle fonctionnalité métier :
1. Chaque modification doit être accompagnée de son test automatisé.
2. Tout commit doit être atomique (un commit = une fonctionnalité + son test).
3. Ne jamais introduire de code sans valider `vendor/bin/pint` et `php artisan test`.

## Standards de Code & Commandes
- **Tests :** `php artisan test` (Unit et Feature dans `tests/`)
- **Linter PHP :** `vendor/bin/pint --test` (exécuter `vendor/bin/pint` pour corriger)
- **CI/CD :** Assurer la compatibilité avec `.github/workflows/ci.yml`
- **Frontend :** Utiliser des notifications toast à la place d'alert() dans JS, centraliser les erreurs avec `logClientError`.
- **Base de données :** SQLite en mémoire pour les tests, RefreshDatabase trait.

## Ordre de Remise à Niveau (Backlog Imposé)
1. Créer la suite de tests pour CartService et OrderService.
2. Créer le pipeline GitHub Actions (`.github/workflows/ci.yml`).
3. Ajouter `docker-compose.yml` complet (PHP 8.3, MySQL, Nginx).
4. Réécrire entièrement `README.md` avec les étapes d'installation et de test.
5. Refactoriser `resources/js/cart.js` (remplacer les alertes par des UI toasts).

"Ce projet suit la feuille de route et les règles définies dans .github/copilot-instructions.md."