# CHANGELOG

Todas las modificaciones relevantes de este paquete se documentarán en este archivo.

## [Unreleased]

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