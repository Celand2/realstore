# Directives du projet RealStore pour Copilot

> Notice de documentation : les fichiers Markdown du dépôt doivent être maintenus à jour à chaque changement. Les instructions, le README et les notes du projet reflètent l’état réel du dépôt et doivent être synchronisés avec les évolutions fonctionnelles.

## RÈGLES D'OR DU PROJET
1. **Priorité absolue :** Couverture de tests et propreté du code (Objectif : rendre le dépôt 100 % autonome et testé).
2. **Workflow obligatoire :** Chaque modification ou nouvelle fonctionnalité métier DOIT inclure son test PHPUnit/Pest.
3. **Commits atomiques :** 1 fonctionnalité/correctif + ses tests = 1 commit.
4. **Qualité :** Valider systématiquement avec `vendor/bin/pint` et `php artisan test`.

## CONVENTIONS DE CODE
- **Backend :** Laravel 12, PHP 8.3 avec typage strict et exceptions explicites (`RuntimeException`).
- **Tests :** Utiliser SQLite en mémoire (`:memory:`) et le trait `RefreshDatabase` pour les tests Feature.
- **Frontend :** Interdiction d'utiliser `alert()`. Utiliser des notifications non bloquantes (toasts) et centraliser les erreurs JS via `logClientError`.

## FEUILLE DE ROUTE DE REMISE À NIVEAU (À exécuter dans l'ordre)

### Étape 1 : Tests automatisés
- Créer `tests/Unit/Services/CartServiceTest.php` (méthodes addProducts, updateItem, removeItem, gestion du stock).
- Créer `tests/Unit/Services/OrderServiceTest.php`.
- Créer `tests/Feature/CartControllerTest.php` pour valider la route `/client/add-cart`.

### Étape 2 : CI/CD et Docker
- Créer `.github/workflows/ci.yml` (exécuter composer install, npm ci, pint --test, php artisan test).
- Créer `docker-compose.yml` (PHP 8.3, Nginx, MySQL) pour permettre une exécution 100 % isolée.

### Étape 3 : Documentation et Clean Code
- Réécrire `README.md` (guide pas à pas depuis un clone vierge, commandes de test, architecture).
- Refactoriser `resources/js/cart.js` pour supprimer les `alert()` et gérer proprement les erreurs UI.