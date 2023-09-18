<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ApiService;
use App\Services\DataProcessor;
use App\Services\StorageService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ApiService::class, function ($app) {
            return new ApiService();
        });
    
        $this->app->bind(DataProcessor::class, function ($app) {
            return new DataProcessor();
        });
    
        $this->app->bind(StorageService::class, function ($app) {
            return new StorageService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
