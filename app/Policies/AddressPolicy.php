<?php

namespace App\Policies;

use App\Models\V1\Address;
use App\Models\V1\Customer;
use App\Models\V1\User;
use Illuminate\Auth\Access\Response;

class AddressPolicy
{

    public function before(User $user): bool|null
    {
        return $user->isAdmin() ? true : null;
    }

    public function createUserAddress(User $authUser, User $user): bool
    {

        return $authUser->isDriver() && $authUser->is($user);
    }

    public function createCustomerAddress(User $authUser, Customer $customer): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Address $address): bool
    {
        return $user->isDriver() && $user->is($address->addressable);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Address $address): bool
    {
        return $user->isDriver() && $user->is($address->addressable);
    }

}
