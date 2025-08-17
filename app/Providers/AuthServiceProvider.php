<?php

namespace App\Providers;

use App\Faker\AddressProvider;
use App\Models\V1\Address;
use App\Policies\AddressPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        Address::class => AddressProvider::class,
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('createUserAddress', [AddressPolicy::class, 'createUserAddress']);
        Gate::define('reports', fn ($user) => $user->isAdmin());
    }
}
