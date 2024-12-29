<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    // Asegúrate de que el directorio donde crearás los archivos existe
    File::ensureDirectoryExists(app_path('Repositories'));
});

// Estas son las pruebas reales que estás escribiendo
it('creates a repository file', function () {
    $repositoryName = 'TestRepository';

    // Ejecuta el comando
    Artisan::call('make:repository', ['name' => $repositoryName]);

    $this->assertFileExists(app_path("Repositories/{$repositoryName}.php"));

    // Opcional: Puedes probar el contenido del archivo creado
    $content = File::get(app_path("Repositories/{$repositoryName}.php"));
    $this->assertStringContainsString('class TestRepository', $content);
});

// Limpieza si es necesario
afterEach(function () {
    // Elimina el archivo creado después de la prueba
    File::delete(app_path('Repositories/TestRepository.php'));
});
