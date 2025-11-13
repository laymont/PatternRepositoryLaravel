# CHANGELOG

Todas las modificaciones relevantes de este paquete se documentarán en este archivo.

## [Unreleased]
- Pendiente

## [1.0.0] - 2025-11-13

### Compatibilidad
- Laravel Framework: ^12.0
- PHP: >=8.3

### Cambios
- ServiceProvider
  - `mergeConfigFrom` movido a `register` y `publishes` mantenido en `boot`.
- Paginación
  - `HandlePerPageTrait::getPerPage` ahora usa `Request::integer('per_page', 10)`.
- Repositorio base
  - `AbstractRepository::find` retorna `Model` (no nullable) al usar `findOrFail`.
- Consola
  - Firma del comando avanzado: `lay:repository:enhanced` para evitar colisiones con `lay:repository`.
- Composer
  - `php` actualizado a `>=8.3`; dependencias `illuminate/*` y `laravel/framework` a `^12.0`.

### Notas
- Publicación de recursos:
  - `vendor:publish --tag=laymont-pattern-repository-config`
  - `vendor:publish --tag=laymont-pattern-repository-stubs`
- El uso de `Cache::tags()` requiere un driver con soporte de tags (p. ej. Redis).
