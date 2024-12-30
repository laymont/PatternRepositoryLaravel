<?php

namespace Laymont\PatternRepository\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Laymont\PatternRepository\Console\Commands\MakeRepositoryCommand;
use Laymont\PatternRepository\Console\Providers\ConsoleServiceProvider;

class PatternRepositoryServiceProvider extends ServiceProvider
{
    /**
     * The package's resource publishing groups.
     */
    public const PUBLISH_GROUP = 'laymont-pattern-repository';

    public function boot(): void
    {
        AboutCommand::add('make:repository', static fn () => ['Version' => '1.0.0']);

        $this->registerPublishing();
        $this->registerCommands();
    }

    public function register(): void
    {
        if ($this->app->environment('local', 'testing')) {
            $this->mergeConfigFrom(__DIR__ . '/../../config/pattern-repository.php', 'pattern-repository');

            $this->app->register(ConsoleServiceProvider::class);
        }
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../../stubs' => base_path('stubs'),
        ], self::PUBLISH_GROUP.'-stubs');
    }

    /**
     *  Register the package's console commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeRepositoryCommand::class,
            ]);
        }
    }
}
