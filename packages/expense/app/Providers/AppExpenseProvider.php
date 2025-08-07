<?php

namespace Packages\expense\app\Providers;
use Illuminate\Support\ServiceProvider;

class AppExpenseProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once __DIR__ . '/../Http/Helpers/data.php';
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'expense');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php', 'expense');
    }
}
