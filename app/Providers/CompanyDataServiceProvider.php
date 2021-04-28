<?php

namespace App\Providers;

use App\Services\APICompanyDataService;
use App\Services\CompanyDataService;
use Illuminate\Support\ServiceProvider;

class CompanyDataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CompanyDataService::class, APICompanyDataService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
