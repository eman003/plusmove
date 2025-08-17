<?php

namespace App\Policies;

use App\Models\V1\Package;
use App\Models\V1\User;
use Illuminate\Auth\Access\Response;

class PackagePolicy
{
    public function before(User $user)
    {
        return $user->isAdmin() ? true : null;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Package $package): bool
    {
        return $user->isDriver() && $user->is($package->driver?->user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Package $package): bool
    {
        return $user->isDriver() && $user->is($package->driver?->user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Package $package): bool
    {
        return false;
    }
}
