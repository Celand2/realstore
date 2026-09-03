# Contribuer a RealStore

Merci de contribuer a RealStore. Les changements doivent rester petits, testables et
compatibles avec Laravel 12 et PHP 8.3.

## Avant de commencer

- Creez une branche dediee depuis la branche principale.
- Utilisez un nom descriptif, par exemple `feature/cart-discounts` ou `fix/login-redirect`.
- Installez les dependances avec `composer install` et `npm ci`.

## Developpement

- Ajoutez ou mettez a jour les tests pour chaque changement fonctionnel.
- Utilisez SQLite en memoire pour les tests.
- N'utilisez pas `alert()` dans le frontend ; affichez une notification non bloquante.
- Maintenez les fichiers Markdown synchronises avec le comportement actuel.

## Validation

```bash
vendor/bin/pint --test
php artisan test
npm run build
```

## Commits et pull requests

- Utilisez le format `type: description`, par exemple `test: cover product listing`.
- Gardez un commit atomique par fonctionnalite ou correctif, avec ses tests.
- Decrivez le comportement modifie et les tests executes dans la pull request.
- Signalez les migrations, changements de configuration et variables d'environnement.
- Une pull request doit etre relue avant fusion.
