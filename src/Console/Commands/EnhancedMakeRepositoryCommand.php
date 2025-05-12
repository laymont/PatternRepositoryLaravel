<?php

namespace Laymont\PatternRepository\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class EnhancedMakeRepositoryCommand extends Command
{
    protected $signature = 'lay:repository {name} {--model=} {--abstract} {--criteria} {--force}'
        . ' {--interfaces=full : Type of interfaces to generate (full, read, write, separate)}'
        . ' {--dir=default : Directory structure to use (default, domain)}'; 

    protected $description = 'Create a new enhanced repository with interfaces following SOLID principles';

    public function handle(): void
    {
        $name = Str::studly($this->argument('name'));
        $modelName = $this->option('model') ?? $name;
        $interfaceType = $this->option('interfaces');
        $directoryStructure = $this->option('dir');
        $useAbstract = $this->option('abstract');
        $useCriteria = $this->option('criteria');
        
        // Setup paths based on directory structure
        $paths = $this->setupPaths($name, $directoryStructure);
        
        // Check for existing files
        if (!$this->checkExistingFiles($paths) && !$this->option('force')) {
            return;
        }
        
        // Create directories
        $this->createDirectories($paths['directories']);
        
        // Generate interfaces based on type
        $this->generateInterfaces($name, $paths, $interfaceType);
        
        // Generate repository based on options
        $this->generateRepository($name, $modelName, $paths, $useAbstract, $useCriteria, $interfaceType);
        
        // Generate abstract repository if requested
        if ($useAbstract) {
            $this->generateAbstractRepository($paths);
        }
        
        $this->info('Repositorio y sus interfaces creados exitosamente.');
        $this->info('Usa el nuevo repositorio con inyecciu00f3n de dependencias en tus controladores.');
    }
    
    protected function setupPaths(string $name, string $directoryStructure): array
    {
        $basePath = app_path();
        
        if ($directoryStructure === 'domain') {
            // Domain-driven structure
            $paths = [
                'directories' => [
                    $basePath . "/Domain/{$name}",
                    $basePath . "/Domain/{$name}/Repositories",
                    $basePath . "/Domain/{$name}/Repositories/Interfaces",
                ],
                'repository' => $basePath . "/Domain/{$name}/Repositories/{$name}Repository.php",
                'abstractRepository' => $basePath . "/Domain/AbstractRepository.php",
                'interfaces' => [
                    'full' => $basePath . "/Domain/{$name}/Repositories/Interfaces/{$name}RepositoryInterface.php",
                    'read' => $basePath . "/Domain/{$name}/Repositories/Interfaces/{$name}ReadRepositoryInterface.php",
                    'write' => $basePath . "/Domain/{$name}/Repositories/Interfaces/{$name}WriteRepositoryInterface.php",
                ],
            ];
        } else {
            // Traditional structure
            $paths = [
                'directories' => [
                    $basePath . "/Repositories",
                    $basePath . "/Repositories/Interfaces",
                    $basePath . "/Repositories/Abstract",
                ],
                'repository' => $basePath . "/Repositories/{$name}Repository.php",
                'abstractRepository' => $basePath . "/Repositories/Abstract/AbstractRepository.php",
                'interfaces' => [
                    'full' => $basePath . "/Repositories/Interfaces/{$name}RepositoryInterface.php",
                    'read' => $basePath . "/Repositories/Interfaces/{$name}ReadRepositoryInterface.php",
                    'write' => $basePath . "/Repositories/Interfaces/{$name}WriteRepositoryInterface.php",
                ],
            ];
        }
        
        return $paths;
    }
    
    protected function checkExistingFiles(array $paths): bool
    {
        if (file_exists($paths['repository']) && !$this->option('force')) {
            $this->error("El repositorio ya existe en {$paths['repository']}. Usa --force para sobrescribirlo.");
            return false;
        }
        
        foreach ($paths['interfaces'] as $path) {
            if (file_exists($path) && !$this->option('force')) {
                $this->error("La interfaz ya existe en {$path}. Usa --force para sobrescribirla.");
                return false;
            }
        }
        
        return true;
    }
    
    protected function createDirectories(array $directories): void
    {
        foreach ($directories as $dir) {
            if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf('No se pudo crear el directorio "%s"', $dir));
            }
        }
    }
    
    protected function generateInterfaces(string $name, array $paths, string $interfaceType): void
    {
        switch ($interfaceType) {
            case 'full':
                // Solo genera la interfaz completa
                $this->createFullInterface($name, $paths['interfaces']['full']);
                break;
                
            case 'separate':
                // Genera interfaces separadas y la combinada
                $this->createReadInterface($name, $paths['interfaces']['read']);
                $this->createWriteInterface($name, $paths['interfaces']['write']);
                $this->createCombinedInterface($name, $paths['interfaces']['full'], 
                    basename($paths['interfaces']['read'], '.php'),
                    basename($paths['interfaces']['write'], '.php')
                );
                break;
                
            case 'read':
                // Solo interfaz de lectura
                $this->createReadInterface($name, $paths['interfaces']['read']);
                break;
                
            case 'write':
                // Solo interfaz de escritura
                $this->createWriteInterface($name, $paths['interfaces']['write']);
                break;
                
            default:
                $this->createFullInterface($name, $paths['interfaces']['full']);
        }
    }
    
    protected function generateRepository(string $name, string $modelName, array $paths, bool $useAbstract, bool $useCriteria, string $interfaceType): void
    {
        if ($useAbstract) {
            // Genera un repositorio que extiende de abstract
            $stub = file_get_contents($this->resolveStubPath('concrete.repository.stub'));
            $abstractNamespace = $this->getNamespaceFromPath($paths['abstractRepository']);
            $interfaceName = $this->getInterfaceNameFromType($name, $interfaceType);
            
            $content = str_replace(
                ['{{ class }}', '{{ namespace }}', '{{ interface }}', '{{ model }}', '{{ abstractNamespace }}'],
                ["{$name}Repository", $this->getNamespaceFromPath($paths['repository']), $interfaceName, $modelName, $abstractNamespace],
                $stub
            );
        } else {
            // Genera un repositorio completo
            $stub = file_get_contents($this->resolveStubPath('repository.stub'));
            $interfaceName = $this->getInterfaceNameFromType($name, $interfaceType);
            
            $content = str_replace(
                ['{{ class }}', '{{ namespace }}', '{{ interface }}', '{{ model }}'],
                ["{$name}Repository", $this->getNamespaceFromPath($paths['repository']), $interfaceName, $modelName],
                $stub
            );
            
            // Au00f1ade imports para Criteria si se solicita
            if ($useCriteria) {
                $content = str_replace(
                    'use Laymont\\PatternRepository\\Concerns\\HandlePerPageTrait;',
                    "use Laymont\\PatternRepository\\Concerns\\HandlePerPageTrait;\nuse Laymont\\PatternRepository\\Concerns\\HasCriteria;",
                    $content
                );
                $content = str_replace(
                    'use HandlePerPageTrait;',
                    'use HandlePerPageTrait, HasCriteria;',
                    $content
                );
            }
        }
        
        file_put_contents($paths['repository'], $content);
    }
    
    protected function generateAbstractRepository(array $paths): void
    {
        if (!file_exists($paths['abstractRepository']) || $this->option('force')) {
            $stub = file_get_contents($this->resolveStubPath('abstract.repository.stub'));
            $namespace = $this->getNamespaceFromPath($paths['abstractRepository']);
            $className = basename($paths['abstractRepository'], '.php');
            
            $content = str_replace(
                ['{{ namespace }}', '{{ class }}'],
                [$namespace, $className],
                $stub
            );
            
            file_put_contents($paths['abstractRepository'], $content);
            $this->info("Repositorio abstracto generado en: {$paths['abstractRepository']}");
        }
    }
    
    protected function createFullInterface(string $name, string $path): void
    {
        $stub = file_get_contents($this->resolveStubPath('interface.repository.stub'));
        $namespace = $this->getNamespaceFromPath($path);
        $className = basename($path, '.php');
        
        $content = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            [$namespace, $className],
            $stub
        );
        
        file_put_contents($path, $content);
    }
    
    protected function createReadInterface(string $name, string $path): void
    {
        $stub = file_get_contents($this->resolveStubPath('interface.readable.repository.stub'));
        $namespace = $this->getNamespaceFromPath($path);
        $className = basename($path, '.php');
        
        $content = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            [$namespace, $className],
            $stub
        );
        
        file_put_contents($path, $content);
    }
    
    protected function createWriteInterface(string $name, string $path): void
    {
        $stub = file_get_contents($this->resolveStubPath('interface.writable.repository.stub'));
        $namespace = $this->getNamespaceFromPath($path);
        $className = basename($path, '.php');
        
        $content = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            [$namespace, $className],
            $stub
        );
        
        file_put_contents($path, $content);
    }
    
    protected function createCombinedInterface(string $name, string $path, string $readableInterface, string $writableInterface): void
    {
        $stub = file_get_contents($this->resolveStubPath('interface.combined.repository.stub'));
        $namespace = $this->getNamespaceFromPath($path);
        $className = basename($path, '.php');
        
        $content = str_replace(
            ['{{ namespace }}', '{{ class }}', '{{ readableInterfaceName }}', '{{ writableInterfaceName }}'],
            [$namespace, $className, $readableInterface, $writableInterface],
            $stub
        );
        
        file_put_contents($path, $content);
    }
    
    protected function getNamespaceFromPath(string $path): string
    {
        $appPath = app_path();
        $relativePath = str_replace($appPath, '', dirname($path));
        $namespacePrefix = app()->getNamespace();
        
        // Convert path separators to namespace separators
        $namespace = str_replace('/', '\\', $relativePath);
        // Remove leading slash if exists
        $namespace = ltrim($namespace, '\\');
        
        return $namespacePrefix . $namespace;
    }
    
    protected function getInterfaceNameFromType(string $name, string $interfaceType): string
    {
        switch ($interfaceType) {
            case 'read':
                return "{$name}ReadRepositoryInterface";
            case 'write':
                return "{$name}WriteRepositoryInterface";
            default:
                return "{$name}RepositoryInterface";
        }
    }
    
    protected function resolveStubPath(string $stub): string
    {
        return __DIR__ . '/../../stubs/' . $stub;
    }
}
