<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\V1\AddressResource;
use App\Http\Resources\V1\CustomerResource;
use App\Http\Resources\V1\UserResource;
use App\Models\V1\Address;
use App\Models\V1\Customer;
use App\Models\V1\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function createUserAddress(AddressRequest $request, User $user)
    {
        $user->addresses()->create($request->validated());

        return new UserResource($user->loadMissing('addresses'));
    }

    public function createCustomerAddress(AddressRequest $request, Customer $customer)
    {
        $customer->addresses()->create($request->validated());

        return new CustomerResource($customer->loadMissing('addresses'));
    }

    public function update(AddressRequest $request, Address $address)
    {
        $address->update($request->validated());

        return new AddressResource($address);
    }
}
