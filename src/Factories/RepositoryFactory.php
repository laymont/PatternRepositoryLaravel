<?php

namespace Laymont\PatternRepository\Factories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Model;

class RepositoryFactory
{
    /**
     * @param Container $container Contenedor de IoC de Laravel
     */
    public function __construct(protected Container $container) {}

    /**
     * Crea una instancia de un repositorio para un modelo especu00edfico
     *
     * @param string $repositoryClass Clase del repositorio a crear
     * @param string|null $modelClass Clase del modelo a utilizar (opcional)
     * @return mixed La instancia del repositorio
     * @throws BindingResolutionException
     */
    public function make(string $repositoryClass, string $modelClass = null): mixed
    {
        // Si no se especifica el modelo, intentamos inferirlo del repositorio
        if (is_null($modelClass)) {
            $modelClass = $this->resolveModelClass($repositoryClass);
        }

        // Resolvemos el modelo desde el contenedor
        $model = $this->container->make($modelClass);

        // Devolvemos una instancia del repositorio con el modelo
        return $this->container->makeWith($repositoryClass, ['model' => $model]);
    }

    /**
     * Intenta inferir la clase del modelo a partir del nombre del repositorio
     *
     * @param string $repositoryClass
     * @return string
     */
    protected function resolveModelClass(string $repositoryClass): string
    {
        $repositoryClassName = class_basename($repositoryClass);
        $modelName = str_replace(['Repository', 'Repo'], '', $repositoryClassName);

        return "\\App\\Models\\{$modelName}";
    }
}
