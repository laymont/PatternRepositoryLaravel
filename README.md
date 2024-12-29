# PatternRepositoryLaravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laymont/pattern-repository-laravel.svg?style=flat-square)](https://packagist.org/packages/laymont/pattern-repository-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/laymont/pattern-repository-laravel.svg?style=flat-square)](https://packagist.org/packages/laymont/pattern-repository-laravel)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Este paquete de Laravel facilita la implementación del patrón de repositorio para una gestión de datos limpia y organizada.

## ¿Qué es el patrón de repositorio?

El patrón de repositorio es un patrón de diseño que desacopla la capa de acceso a datos de tu lógica de negocio. Esto permite:

*   **Mayor mantenibilidad:** Los cambios en la base de datos no impactan directamente en la lógica de negocio.
*   **Mejor testabilidad:** Puedes crear implementaciones simuladas (mocks) de tus repositorios para probar tu lógica de negocio sin depender de una base de datos real.
*   **Reusabilidad:** Los repositorios se pueden reutilizar en diferentes partes de tu aplicación.
*   **Flexibilidad:** Permite cambiar fácilmente el sistema de persistencia (por ejemplo, cambiar de Eloquent a otro ORM) sin modificar la lógica de negocio.

## Instalación

Puedes instalar el paquete vía Composer:

```bash
composer require laymont/laravel-pattern-repository
