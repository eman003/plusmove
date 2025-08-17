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
use Illuminate\Support\Facades\Gate;

class AddressController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/v1/user/{id}/address",
     * summary="Store a new address for a user",
     * tags={"Addresses"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the user to store a new for the user",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=20),
     * @OA\Property(property="full_name", type="string", example="Thomas Opperman"),
     * @OA\Property(property="phone_number", type="string", example="0574911775"),
     * @OA\Property(property="role", type="string", example="Admin"),
     * @OA\Property(property="email", type="string", format="email", example="mark.grobbelaar@example.com"),
     * @OA\Property(property="created_at", type="string", example="1 day ago"),
     * @OA\Property(property="updated_at", type="string", example="1 day ago"),
     * @OA\Property(
     * property="addresses",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=39),
     * @OA\Property(property="name", type="string", example="Home"),
     * @OA\Property(property="address_line_1", type="string", example="2839 Village Port"),
     * @OA\Property(property="address_line_2", type="string", nullable=true, example="5362 Sandile Skyway Drive"),
     * @OA\Property(property="suburb", type="string", example="Klarinet"),
     * @OA\Property(property="city", type="string", example="Pretoria"),
     * @OA\Property(property="province", type="string", example="North West"),
     * @OA\Property(property="postal_code", type="string", example="1358"),
     * @OA\Property(property="country", type="string", example="South Africa"),
     * @OA\Property(property="created_at", type="string", example="1 day ago")
     * )
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The address line 1 field is required."),
     * @OA\Property(
     * property="errors",
     * type="object",
     * @OA\Property(property="address_line_1", type="array", @OA\Items(type="string", example="The address line 1 field is required."))
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to view the addresses for this user."
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The user with the specified ID was not found."
     * )
     * )
     */
    public function createUserAddress(AddressRequest $request, User $user)
    {
        $user->addresses()->create($request->validated());

        return new UserResource($user->loadMissing('addresses'));
    }

    /**
     * @OA\Post(
     * path="/api/v1/customer/{id}/address",
     * description="Store a new address for a customer",
     * summary="Store a new address for a customer",
     * tags={"Addresses"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the customer to retrieve",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=5),
     * @OA\Property(property="name", type="string", example="Miss Willemse"),
     * @OA\Property(property="phone", type="string", example="012-712-3489"),
     * @OA\Property(property="email", type="string", format="email", example="norman79@example.org"),
     * @OA\Property(property="joined", type="string", example="1 day ago"),
     * @OA\Property(
     * property="customer_addresses",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=71),
     * @OA\Property(property="name", type="string", example="Home"),
     * @OA\Property(property="address_line_1", type="string", example="20288 Nomvula Meadows"),
     * @OA\Property(property="address_line_2", type="string", nullable=true, example="8042 Gary Ridge Road"),
     * @OA\Property(property="suburb", type="string", example="Roodepan"),
     * @OA\Property(property="city", type="string", example="Pietermaritzburg"),
     * @OA\Property(property="province", type="string", example="Northern Cape"),
     * @OA\Property(property="postal_code", type="string", example="3539"),
     * @OA\Property(property="country", type="string", example="South Africa"),
     * @OA\Property(property="created_at", type="string", example="1 day ago")
     * )
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to view this customer."
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The customer with the specified ID was not found."
     * )
     * )
     */
    public function createCustomerAddress(AddressRequest $request, Customer $customer)
    {
        $customer->addresses()->create($request->validated());

        return new CustomerResource($customer->loadMissing('addresses'));
    }

    /**
     * @OA\Put(
     * path="/api/v1/address/{id}",
     * summary="Update an existing address",
     * tags={"Addresses"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the address to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", nullable=true, example="Work"),
     * @OA\Property(property="address_line_1", type="string", example="93087 Wild fig Lodge Ridge"),
     * @OA\Property(property="address_2", type="string", nullable=true, example="7010 Mduduzi Expressway Road"),
     * @OA\Property(property="suburb", type="string", example="Parkhurst"),
     * @OA\Property(property="city", type="string", example="Mbombela (Nelspruit)"),
     * @OA\Property(property="province", type="string", example="Northern Cape"),
     * @OA\Property(property="postal_code", type="string", example="4974"),
     * @OA\Property(property="country", type="string", example="South Africa")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Address updated successfully",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=75),
     * @OA\Property(property="name", type="string", example="Home"),
     * @OA\Property(property="address_line_1", type="string", example="93087 Wild fig Lodge Ridge"),
     * @OA\Property(property="address_line_2", type="string", nullable=true, example="7010 Mduduzi Expressway Road"),
     * @OA\Property(property="suburb", type="string", example="Parkhurst"),
     * @OA\Property(property="city", type="string", example="Mbombela (Nelspruit)"),
     * @OA\Property(property="province", type="string", example="Northern Cape"),
     * @OA\Property(property="postal_code", type="string", example="4974"),
     * @OA\Property(property="country", type="string", example="South Africa"),
     * @OA\Property(property="created_at", type="string", example="1 day ago")
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The address line 1 field is required."),
     * @OA\Property(
     * property="errors",
     * type="object",
     * @OA\Property(property="address_line_1", type="array", @OA\Items(type="string", example="The address line 1 field is required."))
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to update this address."
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The address with the specified ID was not found."
     * )
     * )
     */
    public function update(AddressRequest $request, Address $address)
    {
        Gate::authorize('update', $address);
        $address->update($request->validated());

        return new AddressResource($address);
    }

    /**
     * @OA\Delete(
     * path="/api/v1/address/{id}",
     * summary="Delete an address",
     * tags={"Addresses"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the address to delete",
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\Response(
     * response=200,
     * description="Address deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Address deleted successfully")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The address with the specified ID was not found."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to delete this address."
     * )
     * )
     */
    public function destroy(Address $address)
    {
        Gate::authorize('delete', $address);

        $address->delete();

        return response()->json([
            'message' => 'Address deleted successfully'
        ]);
    }
}
