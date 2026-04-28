# Testing Guide - PatternRepositoryLaravel v4.0.0

## Tests Automatizados

Este paquete incluye **39 tests automatizados** que cubren la funcionalidad principal.

### Ejecutar Tests

```bash
# En el directorio del paquete
cd packages/Laymont/PatternRepositoryLaravel
composer install
./vendor/bin/pest
```

### Coverage

Los tests cubren:

| Componente | Tests | Descripción |
|-----------|-------|-------------|
| Actions | 6 | CreateAction, UpdateAction, DeleteAction |
| EloquentRepository | 10 | find, all, paginate, create, update, delete, query |
| Concerns | 11 | Cacheable, HasCriteria, HasEloquentScopes |
| Contracts | 10 | Validación de interfaces |
| Criteria | 1 | WhereEqualsCriteria |

### Configuración de Tests

- **PHPUnit/Pest**: v11+
- **Database**: SQLite en memoria
- **Testbench**: Orchestra Testbench v11

### Estructura de Tests

```
tests/
├── TestCase.php              # Base test case con SQLite
├── TestModel.php           # Modelo de prueba
└── Unit/
    ├── Actions/
    │   └── ActionsTest.php
    ├── Concerns/
    │   ├── CacheableTest.php
    │   ├── HasCriteriaTest.php
    │   └── HasEloquentScopesTest.php
    ├── Contracts/
    │   └── ContractsTest.php
    ├── Repositories/
    │   └── EloquentRepositoryTest.php
    ├── AbstractRepositoryTest.php
    └── WhereEqualsCriteriaTest.php
```

## Tests Manuales (opcional)

Aún puedes hacer validación manual en un proyecto Laravel:

```bash
# Instalar paquete
composer require laymont/pattern-repository-laravel:dev-feature/v4-tests-and-laravel-13

# Generar repositorio
php artisan make:repository User

# Probar en controlador
```

## Troubleshooting

- Asegúrate de tener PHP 8.3+
- Laravel 13 instalado
- Ejecute `composer update` después de cambios en dependencies