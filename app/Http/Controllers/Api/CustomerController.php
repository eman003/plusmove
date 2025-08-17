<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\V1\CustomerResource;
use App\Models\V1\Customer;
use App\Models\V1\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/customer",
     * summary="Get a list of customers",
     * tags={"Customers"},
     * security={{"sanctum":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=11),
     * @OA\Property(property="name", type="string", example="Karoo Kookery (Pty) Ltd."),
     * @OA\Property(property="phone", type="string", example="27 23 555 6789"),
     * @OA\Property(property="email", type="string", format="email", example="info@karookookery.co.za"),
     * @OA\Property(property="joined", type="string", example="5 hours ago"),
     * @OA\Property(
     * property="customer_addresses",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=81),
     * @OA\Property(property="name", type="string", example="School"),
     * @OA\Property(property="address_line_1", type="string", example="8422 Siyanda Bay"),
     * @OA\Property(property="address_line_2", type="string", nullable=true, example="2946 Mandy Gardens Street"),
     * @OA\Property(property="suburb", type="string", example="Cashan"),
     * @OA\Property(property="city", type="string", example="Bloemfontein"),
     * @OA\Property(property="province", type="string", example="Free State"),
     * @OA\Property(property="postal_code", type="string", example="9215"),
     * @OA\Property(property="country", type="string", example="South Africa"),
     * @OA\Property(property="created_at", type="string", example="1 day ago")
     * )
     * )
     * )
     * ),
     * @OA\Property(
     * property="links",
     * type="object",
     * @OA\Property(property="first", type="string", example="http://plusmove.test/api/v1/customer?page=1"),
     * @OA\Property(property="last", type="string", example="http://plusmove.test/api/v1/customer?page=1"),
     * @OA\Property(property="prev", type="string", nullable=true),
     * @OA\Property(property="next", type="string", nullable=true)
     * ),
     * @OA\Property(
     * property="meta",
     * type="object",
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="from", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=1),
     * @OA\Property(
     * property="links",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="url", type="string", nullable=true),
     * @OA\Property(property="label", type="string", example="&laquo; Previous"),
     * @OA\Property(property="active", type="boolean")
     * )
     * ),
     * @OA\Property(property="path", type="string", example="http://plusmove.test/api/v1/customer"),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="to", type="integer", example=10),
     * @OA\Property(property="total", type="integer", example=10)
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to view the customers."
     * )
     * )
     */
    public function index()
    {
        Gate::authorize('viewAny', Customer::class);

        $customers = Customer::with('addresses')->latest('id')->paginate(15)->withQueryString();

        return CustomerResource::collection($customers);
    }

    /**
     * @OA\Post(
     * path="/api/v1/customer",
     * summary="Create a new customer",
     * tags={"Customers"},
     * security={{"sanctum":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name","phone","email"},
     * @OA\Property(property="name", type="string", example="Karoo Kookery (Pty) Ltd."),
     * @OA\Property(property="phone", type="string", example="27 23 555 6789"),
     * @OA\Property(property="email", type="string", format="email", example="info@karookookery.co.za")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Customer created successfully",
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
     * @OA\Items(type="object")
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The name field is required. (and 2 more errors)"),
     * @OA\Property(
     * property="errors",
     * type="object",
     * @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name field is required.")),
     * @OA\Property(property="phone", type="array", @OA\Items(type="string", example="The phone field is required.")),
     * @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field is required."))
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to create a customer."
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The requested user was not found."
     * )
     * )
     */
    public function store(CustomerRequest $request)
    {
        $customer = Customer::create($request->validated());

        return new CustomerResource($customer->refresh()->loadMissing('addresses'));
    }

    /**
     * @OA\Get(
     * path="/api/v1/customer/{id}",
     * summary="Get a single customer by ID",
     * tags={"Customers"},
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
     * @OA\Property(property="id", type="integer", example=11),
     * @OA\Property(property="name", type="string", example="Karoo Kookery (Pty) Ltd."),
     * @OA\Property(property="phone", type="string", example="27 23 555 6789"),
     * @OA\Property(property="email", type="string", format="email", example="info@karookookery.co.za"),
     * @OA\Property(property="joined", type="string", example="5 hours ago"),
     * @OA\Property(
     * property="customer_addresses",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=81),
     * @OA\Property(property="name", type="string", example="School"),
     * @OA\Property(property="address_line_1", type="string", example="8422 Siyanda Bay"),
     * @OA\Property(property="address_line_2", type="string", nullable=true, example="2946 Mandy Gardens Street"),
     * @OA\Property(property="suburb", type="string", example="Cashan"),
     * @OA\Property(property="city", type="string", example="Bloemfontein"),
     * @OA\Property(property="province", type="string", example="Free State"),
     * @OA\Property(property="postal_code", type="string", example="9215"),
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
    public function show(Customer $customer)
    {
        Gate::authorize('view', $customer);

        return new CustomerResource($customer->loadMissing('addresses'));
    }

    /**
     * @OA\Put(
     * path="/api/v1/customer/{id}",
     * summary="Update an existing customer",
     * tags={"Customers"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the customer to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Karoo Kookery (Pty) Ltd."),
     * @OA\Property(property="phone", type="string", example="27 23 555 6789"),
     * @OA\Property(property="email", type="string", format="email", example="info@karookookery.co.za")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Customer updated successfully",
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
     * @OA\Items(type="object")
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The name field is required. (and 2 more errors)"),
     * @OA\Property(
     * property="errors",
     * type="object",
     * @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name field is required.")),
     * @OA\Property(property="phone", type="array", @OA\Items(type="string", example="The phone field is required.")),
     * @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field is required."))
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to update this customer."
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The customer with the specified ID was not found."
     * )
     * )
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        Gate::authorize('update', $customer);

        $customer->update($request->validated($request->all()));

        return new CustomerResource($customer->refresh()->loadMissing('addresses'));
    }

    /**
     * @OA\Delete(
     * path="/api/v1/customer/{id}",
     * summary="Delete a customer",
     * tags={"Customers"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the customer to delete",
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\Response(
     * response=200,
     * description="Customer deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Customer deleted successfully")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The customer with the specified ID was not found."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to delete this customer."
     * )
     * )
     */
    public function destroy(Customer $customer)
    {
        Gate::authorize('delete', $customer);

        $customer->addresses()->delete();
        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully'
        ]);
    }
}
