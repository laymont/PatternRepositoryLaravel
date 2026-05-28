# CHANGELOG

Todas las modificaciones relevantes de este paquete se documentarán en este archivo.

## [Unreleased]

## [4.0.1] - 2026-05-28

### Seguridad
- Actualizado dependencias de Laravel 13 (v13.6.0 -> v13.12.0) para parches de seguridad de Symfony
- Actualizado paquetes Symfony vulnerables:
  - symfony/polyfill-intl-idn (v1.37.0 -> v1.38.1) - CVE-2026-46644
  - symfony/routing (v7.4.8 -> v7.4.13) - CVE-2026-48784, CVE-2026-45065
  - symfony/yaml (v7.4.8 -> v7.4.13) - CVE-2026-45304, CVE-2026-45305, CVE-2026-45133
- Actualizado orchestra/testbench-core (v11.3.1 -> v11.3.3)

### Cambios
- Actualizado composer.lock con 37 actualizaciones de dependencias
- Tests: 39 tests pasando (65 assertions)

## [4.0.0] - 2026-04-28

### Compatibilidad
- Laravel Framework: `^13.0`
- PHP: `>=8.3`
- Orchestra Testbench: `^11.0`

### Nuevo
- **EloquentRepository**: Nueva clase base que combina:
  - `HasCriteria` - Patrón Criteria
  - `HandlePerPageTrait` - Paginación
  - `HasEloquentScopes` - Global scopes de Eloquent

- **Actions**: Clases separadas para operaciones atómicas:
  - `CreateAction` - Crear registros
  - `UpdateAction` - Actualizar registros
  - `DeleteAction` - Eliminar registros

- **Contracts**: Interfaces mejoradas:
  - `RepositoryInterface` - Interfaz completa
  - `ReadableRepositoryInterface` - Solo lectura
  - `WritableRepositoryInterface` - Solo escritura
  - `CriteriaInterface` - Para criteria

- **Test Suite**: 39 tests automatizados con SQLite en memoria:
  - Actions: Create, Update, Delete
  - EloquentRepository: find, all, paginate, create, update, delete
  - Concerns: Cacheable, HasCriteria, HasEloquentScopes
  - Contracts: Validación de interfaces

### Notas
- Breaking change: Laravel 13 requerido
- Tests ahora son automatizados (antes solo validación manual)

---

## [3.0.0] - 2024-12-29

### Compatibilidad
- Laravel Framework: `^12.0`
- PHP: `>=8.3`

### Cambios
- ServiceProvider: `mergeConfigFrom` movido a `register`
- Paginación: `HandlePerPageTrait::getPerPage` usa `Request::integer`
- Repositorio base: `find` retorna `Model` con `findOrFail`
- Composer: dependencias actualizadas a `^12.0`

---

## [2.0.0] - release anterior

### Características
- Principios SOLID y SRP
- Patrón Criteria
- Soporte para caché
- Factory de repositorios
- Generador avanzado

---

## [1.0.0] - 2024-11-13

### Lanzamiento inicial
- Implementación básica del patrón repositorio
- Comando Artisan `lay:repository`
- Soporte para interfaces separadas
