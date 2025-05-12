# Pruebas manuales para PatternRepositoryLaravel

## Checklist de validación manual en Laravel 12

1. **Instala el paquete en un proyecto Laravel 12**
   ```bash
   composer require laymont/laravel-pattern-repository:dev-main
   ```

2. **Publica los stubs (opcional)**
   ```bash
   php artisan vendor:publish --tag=laymont-pattern-repository-stubs
   ```

3. **Genera un repositorio usando el comando Artisan**
   ```bash
   php artisan lay:repository User --abstract --interfaces=separate --force
   ```

4. **Verifica que los archivos se generaron**
   - app/Repositories/UserRepository.php
   - app/Repositories/Interfaces/UserRepositoryInterface.php
   - app/Repositories/Interfaces/UserReadRepositoryInterface.php
   - app/Repositories/Interfaces/UserWriteRepositoryInterface.php

5. **Usa el repositorio en un controlador**
   ```php
   use App\Repositories\UserRepository;

   public function index(UserRepository $repo) {
       $users = $repo->getAll();
       // ...
   }
   ```

6. **Personaliza los stubs publicados si lo deseas**
   - Edita los archivos en stubs/pattern-repository

7. **Prueba la funcionalidad en la aplicación**
   - Accede a rutas/controladores que usen el repositorio generado.
   - Verifica que no hay errores y que los métodos funcionan.

8. **Solución de problemas**
   - Si falla algún paso, revisa los logs de Laravel y la consola.
   - Asegúrate de estar en Laravel 12 y PHP 8.2+.
   - Reporta issues en el repositorio del paquete.
