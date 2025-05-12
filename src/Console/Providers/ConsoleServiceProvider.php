<?php

namespace Laymont\PatternRepository\Console\Providers;

use Illuminate\Support\ServiceProvider;
use Laymont\PatternRepository\Console\Commands\MakeRepositoryCommand;
use Laymont\PatternRepository\Console\Commands\EnhancedMakeRepositoryCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeRepositoryCommand::class,
                EnhancedMakeRepositoryCommand::class,
            ]);
        }
    }
}
