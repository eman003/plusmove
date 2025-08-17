<?php

namespace App\Providers;

use App\Faker\AddressElement;
use App\Models\V1\Package;
use App\Helper\LeastLoadedDriver;
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

        $this->app->singleton('driver', function () {
            return new LeastLoadedDriver();
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();
        Model::preventLazyLoading(!app()->isProduction());

        Package::observe(\App\Observers\PackageObserver::class);
    }
}
