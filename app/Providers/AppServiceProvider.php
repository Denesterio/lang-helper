<?php

namespace App\Providers;

use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\WordController;
use App\Repositories\RepositoryFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
