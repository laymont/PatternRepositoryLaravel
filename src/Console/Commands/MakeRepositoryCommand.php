<?php

namespace Laymont\PatternRepository\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'lay:repository {name} {--force}';

    protected $description = 'Create a new repository and its interface';

    public function handle(): void
    {
        $name = Str::studly($this->argument('name'));
        $modelName = $name;

        $interfacePath = $this->getPath($name, 'Interface');
        $repositoryPath = $this->getPath($name, 'Repository');

        if (file_exists($interfacePath) && ! $this->option('force')) {
            $this->error("La interfaz ya existe en {$interfacePath}. Usa --force para sobrescribirla.");

            return;
        }

        if (file_exists($repositoryPath) && ! $this->option('force')) {
            $this->error("El repositorio ya existe en {$repositoryPath}. Usa --force para sobrescribirla.");

            return;
        }
        $this->createDirectories();
        $this->createInterface($name, $interfacePath);
        $this->createRepository($name, $repositoryPath, $modelName);
        $this->info('Interfaz y repositorio creados con éxito.');
    }

    protected function createDirectories(): void
    {
        if (! is_dir(app_path('Repositories')) && ! mkdir($concurrentDirectory = app_path('Repositories'), 0755, true) && ! is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
        if (! is_dir(app_path('Repositories/Interfaces')) && ! mkdir($concurrentDirectory = app_path('Repositories/Interfaces'), 0755, true) && ! is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
    }

    protected function createInterface(string $name, string $path): void
    {
        $stub = file_get_contents($this->getInterfaceStub());
        $namespace = $this->getDefaultNamespace(app()->getNamespace(), 'Interfaces');
        $content = str_replace(
            ['{{ class }}', '{{ namespace }}'],
            ["{$name}Interface", $namespace],
            $stub
        );
        file_put_contents($path, $content);
    }

    protected function createRepository(string $name, string $path, string $model): void
    {
        $stub = file_get_contents($this->getStub());
        $namespace = $this->getDefaultNamespace(app()->getNamespace(), '');
        $content = str_replace(
            ['{{ class }}', '{{ namespace }}', '{{ interface }}', '{{ model }}'],
            ["{$name}Repository", $namespace, "{$name}Interface", $model],
            $stub
        );
        file_put_contents($path, $content);
    }

    protected function getPath(string $name, string $type): string
    {
        if ($type === 'Interface') {
            return app_path("Repositories/Interfaces/{$name}Interface.php");
        }

        return app_path("Repositories/{$name}Repository.php");
    }

    protected function getDefaultNamespace(string $rootNamespace, string $typeNamespace): string
    {
        // Elimina la parte donde añades la barra invertida extra.
        return rtrim($rootNamespace, '\\').'\\Repositories'.($typeNamespace ? '\\'.$typeNamespace : '');

    }

    protected function getStub(): string
    {
        return $this->resolveStubPath('repository.stub');
    }

    protected function getInterfaceStub(): string
    {
        return $this->resolveStubPath('interface.repository.stub');
    }

    protected function resolveStubPath(string $stub): string
    {
        return base_path('packages/Laymont/PatternRepository/stubs/'.$stub);
    }
}
