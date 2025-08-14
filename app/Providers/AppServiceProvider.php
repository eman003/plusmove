<?php

namespace App\Providers;

use App\Faker\AddressElement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('address', function () {
            return new AddressElement();
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();
        Model::preventLazyLoading(!app()->isProduction());
    }
}
