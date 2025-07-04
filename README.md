# Laravel Pattern Repository

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laymont/pattern-repository-laravel.svg?style=flat-square)](https://packagist.org/packages/laymont/pattern-repository-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/laymont/pattern-repository-laravel.svg?style=flat-square)](https://packagist.org/packages/laymont/pattern-repository-laravel)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Este paquete de Laravel simplifica la implementación del patrón repositorio, ayudando a crear aplicaciones con una arquitectura limpia, mantenible y escalable.

## ¿Qué es el Patrón Repositorio?

El patrón repositorio actúa como una capa de abstracción entre la lógica de negocio y la capa de acceso a datos. Al usar repositorios, tus controladores y servicios interactúan con los datos a través de interfaces, sin necesidad de conocer la implementación concreta del acceso a datos (por ejemplo, consultas a la base de datos). Esto aporta múltiples ventajas:

*   **Desacoplamiento:** Reduce la dependencia entre las diferentes capas de la aplicación.
*   **Facilidad de prueba:** Permite realizar pruebas unitarias de la lógica de negocio sin necesidad de una base de datos real (usando mocks).
*   **Flexibilidad:** Facilita el cambio de la implementación del acceso a datos (por ejemplo, cambiar de base de datos o usar un ORM diferente) sin afectar la lógica de negocio.
*   **Código organizado:** Promueve un código más limpio, estructurado y fácil de mantener.
*   **Reutilización:** Facilita la reutilización de la lógica de acceso a datos en diferentes partes de la aplicación.

## Compatibilidad

- **Laravel Framework:** `^12.0`
- **PHP:** `^8.2`

## Instalación

Puedes instalar el paquete usando Composer:

```bash
composer require laymont/laravel-pattern-repository
```
El paquete se autodescubre, por lo que no es necesario registrar el ServiceProvider.

### Que hace este paquete
1. Crear una Interfaz de Repositorio
   Comienza definiendo una interfaz para tu repositorio en la carpeta app/Repositories/ (si no existe, crea la carpeta). Por ejemplo, para un repositorio de usuarios:
```php
// app/Repositories/UserInterface.php
namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface UserInterface
{
    /**
    * Get all records.
    * @return Collection
    */
    public function getAll(): Collection;

    /**
    * Get all records with pagination.
    * @param Request $request
    */
    public function getAllPaginate(Request $request);

    /**
    * Find a record by its id.
    * @param int $id
    * @return Model|null
    */
    public function find(mixed $id): ?Model;

    /**
    * Create a new record.
    * @param array $attributes
    * @return Model
    */
    public function create(array $attributes): Model;

    /**
    * Update an existing record.
    * @param int $id
    * @param array $attributes
    * @return bool
    */
    public function update(int $id, array $attributes): bool;

    /**
    * Delete an existing record.
    * @param int $id
    * @return bool
    */
    public function delete(int $id): bool;
}
```
2. Crear una Implementación de Repositorio
   Luego, crea una clase que implemente la interfaz del repositorio en la carpeta app/Repositories/Eloquent/. Por ejemplo, para el repositorio de usuarios usando Eloquent:
```php
// app/Repositories/UserRepository.php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Laymont\PatternRepository\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Laymont\PatternRepository\Concerns\HandlePerPageTrait;

class UserRepository implements UserInterface
{
    use HandlePerPageTrait;

    public function __construct(protected User $model) {}

    /**
    * Get all records.
    * @return Collection
    */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
    * Get all records with pagination.
    * @param Request $request
    * @return mixed
    */
    public function getAllPaginate(Request $request): mixed
    {
        return $this->model::paginate($this->getPerPage($request))->withQueryString();
    }

    /**
    * Find a record by its id.
    * @param int $id
    * @return Model|null
    */
    public function find(mixed $id): ?Model
    {
        return $this->model::query()->findOrFail($id);
    }

    /**
    * Create a new record.
    * @param array $attributes
    * @return Model
    * @throws RepositoryException
    */
    public function create(array $attributes): Model
    {
         try {
            return DB::transaction(function () use ($attributes) {
                return $this->model::create($attributes);
            });
        } catch (Throwable $e) {
            Log::error('Error al crear registro User ' , ['message' => $e->getMessage()]);
            throw new RepositoryException('Error al crear registro User', 0, $e);
        }
    }

    /**
    * Update an existing record.
    * @param int $id
    * @param array $attributes
    * @return bool
    * @throws RepositoryException
    */
    public function update(int $id, array $attributes): bool
    {
         try {
            return DB::transaction(function () use ($id, $attributes) {
                return $this->model::query()->where('id', $id)->update($attributes);
            });
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException('Registro no encontrado.', 404, $e);
        }catch (Throwable $e) {
            Log::error( 'Error al actualizar el registro: ' . $e->getMessage());
            throw new RepositoryException('Error al actualizar el registro.', 0, $e);
        }
    }

    /**
    * Delete an existing record.
    * @param int $id
    * @return bool
    * @throws RepositoryException
    */
    public function delete(int $id): bool
    {
          try {
            return (bool) $this->model::destroy($id);
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException('Registro no encontrado.', 404, $e);
        } catch (Throwable $exception) {
            Log::error('Error al eliminar el registro: '.$exception->getMessage());
            throw new RepositoryException('Error al eliminar el registro.', 0, $exception);
        }
    }
}
```

### Como se usa
Luego de instalar el paquete desde la consola del proyecto ejecute por ejemplo
```bash
php artisan lay:repository User 
```
Donde **User** representa el modelo al cual se le implementara el patron de repositorio, remplace el nombre del modelo
por el nombre del modelo al cual planea implementarle el patron de repositorio.
Como resultado se crearán dos archivos en app/Repositories
- app/Repositories/Interface/UserInterface.php
- app/Repositories/UserRepository.php

### El siguiente paso es registrar la implementación del patron de repositorio
Para registrar tu repositorio en el contenedor de dependencias de Laravel, debes usar un ServiceProvider. Puedes generar 
uno usando el comando de Artisan:
```bash
php artisan make:provider RepositoryServiceProvider
```
Luego actualiza el archivo generado app/Providers/RepositoryServiceProvider.php con lo siguiente:
```php
<?php

namespace App\Providers;

use App\Repositories\UserRepositoryInterface;
use App\Repositories\Eloquent\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
    }
}
```

### Usar el Repositorio en tu Controlador o Servicio
Ahora puedes inyectar la interfaz de repositorio en tu controlador o servicio:
```php
use App\Repositories\UserRepositoryInterface;

class UserController extends Controller
{

    public function __construct(protected UserInterface $userRepository) {}

    public function index()
    {
        $users = $this->userRepository->all();
        // ...
    }
}
```

## Checklist de validación manual en Laravel 12

1. Instala el paquete en un proyecto Laravel 12
2. Publica los stubs (opcional)
3. Genera un repositorio con Artisan
4. Verifica que los archivos se generaron correctamente
5. Usa el repositorio en un controlador
6. Personaliza los stubs si lo deseas
7. Prueba la funcionalidad en la aplicación
8. Si hay errores, revisa logs y dependencias

## Pruebas

- Los tests unitarios incluidos solo validan la lógica interna del paquete.
- La integración y comandos Artisan deben probarse manualmente siguiendo el checklist anterior.

## Troubleshooting

- Asegúrate de estar usando Laravel 12 y PHP 8.2+.
- Si tienes problemas con la generación de archivos, revisa permisos y rutas.
- Reporta issues en el repositorio del paquete si encuentras bugs.

---

Para una guía más detallada de pruebas manuales, revisa el archivo TESTING.md incluido en este repositorio.

## Características avanzadas del paquete (v2+)

Este paquete ahora soporta:

- **Principios SOLID y SRP**: Interfaces separadas para lectura y escritura, repositorio abstracto reutilizable, y código desacoplado.
- **Patrón Criteria**: Permite aplicar filtros reutilizables y flexibles a las consultas del repositorio.
- **Soporte para caché**: Puedes cachear resultados de consultas en tus repositorios para mejorar el rendimiento.
- **Factory de repositorios**: Para instanciar repositorios de manera flexible y desacoplada.
- **Generador de código avanzado**: Usa el comando `lay:repository` con opciones para generar solo interfaces de lectura, escritura, ambas, o separadas, y para elegir estructura de carpetas (tradicional o DDD).

### Ejemplo de uso del patrón Criteria

```php
use Laymont\PatternRepository\Criteria\WhereEqualsCriteria;
use App\Repositories\UserRepository;

$userRepo = app(UserRepository::class);
$userRepo->pushCriteria(new WhereEqualsCriteria('status', 'active'));
$users = $userRepo->getAll();
```

Puedes crear tus propios criterios implementando la interfaz `CriteriaInterface`.

### Ejemplo de uso del Factory

```php
use Laymont\PatternRepository\Factories\RepositoryFactory;

$factory = app(RepositoryFactory::class);
$userRepo = $factory->make(App\Repositories\UserRepository::class);
```

### Ejemplo de repositorio cacheable

```php
use Laymont\PatternRepository\Concerns\CacheableRepository;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    use CacheableRepository;
    // ...
    public function __construct(User $model) {
        parent::__construct($model);
        $this->bootCacheableRepository();
    }
    public function getAll(): Collection
    {
        return $this->cacheResult('all', fn() => parent::getAll());
    }
}
```

### Comando Artisan mejorado

```bash
php artisan lay:repository User --abstract --criteria --interfaces=separate --dir=domain
```
- `--abstract`: Usa un repositorio base abstracto.
- `--criteria`: Habilita el patrón criteria.
- `--interfaces=separate`: Genera interfaces separadas para lectura y escritura.
- `--dir=domain`: Usa estructura DDD (Domain-Driven Design).

### Configuración avanzada

Puedes personalizar el comportamiento global en `config/pattern-repository.php`:

```php
return [
    'namespace' => 'App\\Repositories',
    'path' => app_path('Repositories'),
    'structure' => 'default',
    'use_abstract' => true,
    'models_namespace' => 'App\\Models',
    'default_interface_type' => 'full',
    'use_criteria' => true,
    'enable_cache' => false,
    'cache_ttl' => 60,
    'cache_tags' => ['repositories'],
    'debug_mode' => env('APP_DEBUG', false),
    'pagination' => [
        'default_per_page' => 15,
        'max_per_page' => 100,
    ],
];
```

## Donaciones

Si encuentras útil este paquete y deseas apoyar su desarrollo y mantenimiento, puedes considerar hacer una donación. Tu apoyo es muy apreciado y nos motiva a seguir mejorando el paquete.

Para recibir donaciones, puedes utilizar la siguiente opción:

### Binance Pay

Si tienes una cuenta en Binance, puedes apoyarnos directamente a través de Binance Pay:

1.  **Abre la aplicación de Binance.**
2.  **Ve a la sección de Binance Pay.**
3.  **Utiliza la opción "Enviar" o "Transferir".**
4.  **Ingresa mi Binance Pay ID:** `206414132`
    *   **(Opcional):** También puedes escanear el siguiente código QR:

        ![QR Code](https://i.imgur.com/mPDvYyW.jpeg)

        *Si no has generado tu código QR y no sabes donde, sigue estos pasos:*
        - *En la aplicación de Binance, ve a la sección de Binance Pay*
        - *Pulsa sobre el icono de "Recibir"*
        - *Allí podrás ver y descargar tu código QR*
5.  **Indica en la descripción o nota de la transacción que es una donación para este paquete de Laravel.**
6.  **Ingresa el monto que deseas donar.**
7.  **Confirma la transacción.**

**Nota:** Asegúrate de revisar el destinatario y la información de la transacción antes de confirmar el envío.

### Otras Opciones
Si no tienes una cuenta en Binance o prefieres otras opciones, puedes contactarme directamente a través de correo electrónico o formulario de contacto para coordinar otros métodos de envío.

¡Gracias por tu apoyo!

## Contribución
Si deseas contribuir a este paquete, puedes seguir estos pasos:

1. Haz un fork del repositorio.
2. Crea una rama con tu nombre: git checkout -b mi-nueva-funcionalidad.
3. Realiza los cambios y haz commit: git commit -m "Agregando una nueva funcionalidad".
4. Sube tus cambios: git push origin mi-nueva-funcionalidad.
5. Abre un Pull Request hacia la rama main.
