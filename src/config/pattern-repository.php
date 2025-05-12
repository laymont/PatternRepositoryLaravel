<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de Repositorios
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar la configuración global para todos los repositorios
    | generados por este paquete.
    |
    */
    
    // Namespace base para los repositorios
    'namespace' => 'App\\Repositories',
    
    // Ruta base donde se crearán los repositorios
    'path' => app_path('Repositories'),
    
    // Estructura de directorios a utilizar (default, domain)
    'structure' => 'default',
    
    // Determina si se debe usar un repositorio base abstracto
    'use_abstract' => true,
    
    // Namespace base para los modelos
    'models_namespace' => 'App\\Models',
    
    // Tipo de interfaces a generar por defecto (full, read, write, separate)
    'default_interface_type' => 'full',
    
    // Habilitar el uso de criterios de búsqueda
    'use_criteria' => true,
    
    // Habilitar el cacheo de resultados en los repositorios (característica futura)
    'enable_cache' => false,
    
    // Tiempo de vida predeterminado para la caché (en minutos)
    'cache_ttl' => 60,
    
    // Tags de caché por defecto
    'cache_tags' => ['repositories'],
    
    // Habilitar registro de consultas para depuración
    'debug_mode' => env('APP_DEBUG', false),
    
    // Opciones de paginación
    'pagination' => [
        'default_per_page' => 15,
        'max_per_page' => 100,
    ],
];
