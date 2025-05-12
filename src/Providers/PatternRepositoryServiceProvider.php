<?php

namespace Laymont\PatternRepository\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Laymont\PatternRepository\Console\Providers\ConsoleServiceProvider;
use Laymont\PatternRepository\Factories\RepositoryFactory;

class PatternRepositoryServiceProvider extends ServiceProvider
{
    /**
     * The package's resource publishing groups.
     */
    public const PUBLISH_GROUP = 'laymont-pattern-repository';

    public function boot(): void
    {
        AboutCommand::add('make:repository', static fn () => ['Version' => '1.0.0']);

        // Verificar siempre la publicación y la fusión de la configuración.
        $this->mergeConfigFrom(__DIR__.'/../config/pattern-repository.php', 'pattern-repository');
        $this->registerPublishing();
    }

    public function register(): void
    {
        // Registrar el Factory como un singleton en el contenedor
        $this->app->singleton(RepositoryFactory::class, function ($app) {
            return new RepositoryFactory($app);
        });

        // Crear un alias para facilitar el acceso al Factory
        $this->app->alias(RepositoryFactory::class, 'repository.factory');

        // Registrar provider de comandos de consola
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(ConsoleServiceProvider::class);
        }
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/pattern-repository.php' => config_path('pattern-repository.php'),
        ], self::PUBLISH_GROUP.'-config');

        // Publicar los stubs para permitir personalización
        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs/pattern-repository'),
        ], self::PUBLISH_GROUP.'-stubs');
    }
}
