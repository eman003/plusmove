<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverRequest;
use App\Http\Resources\V1\DriverResource;
use App\Models\V1\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DriverController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/driver",
     * summary="Get a list of drivers",
     * tags={"Drivers"},
     * security={{"sanctum":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=8),
     * @OA\Property(property="full_name", type="string", nullable=true, example="Chantelle Murray"),
     * @OA\Property(property="city", type="string", nullable=true, example="Ekangala"),
     * @OA\Property(property="vehicle_make", type="string", example="Toyota"),
     * @OA\Property(property="vehicle_model", type="string", example="Tacoma"),
     * @OA\Property(property="vehicle_colour", type="string", example="Pearl White"),
     * @OA\Property(property="vehicle_registration_number", type="string", example="HV63PRGP"),
     * @OA\Property(property="driver_license_expires_at", type="string", example="2028-09-12")
     * )
     * ),
     * @OA\Property(
     * property="links",
     * type="object",
     * @OA\Property(property="first", type="string", nullable=true, example="http://plusmove.test/api/v1/driver?page=1"),
     * @OA\Property(property="last", type="string", nullable=true, example="http://plusmove.test/api/v1/driver?page=1"),
     * @OA\Property(property="prev", type="string", nullable=true),
     * @OA\Property(property="next", type="string", nullable=true)
     * ),
     * @OA\Property(
     * property="meta",
     * type="object",
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="from", type="integer", nullable=true, example=1),
     * @OA\Property(property="last_page", type="integer", example=1),
     * @OA\Property(
     * property="links",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="url", type="string", nullable=true),
     * @OA\Property(property="label", type="string"),
     * @OA\Property(property="page", type="integer", nullable=true),
     * @OA\Property(property="active", type="boolean")
     * )
     * ),
     * @OA\Property(property="path", type="string", example="http://plusmove.test/api/v1/driver"),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="to", type="integer", nullable=true, example=7),
     * @OA\Property(property="total", type="integer", example=7)
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to view the drivers."
     * )
     * )
     */
    public function index()
    {
        Gate::authorize('viewAny', Driver::class);

        $drivers = Driver::latest('id')->paginate(15)->withQueryString();

        return DriverResource::collection($drivers);
    }

    /**
     * @OA\Post(
     * path="/api/v1/driver",
     * summary="Create a new driver",
     * tags={"Drivers"},
     * security={{"sanctum":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"user_id","vehicle_make","vehicle_model","vehicle_colour","vehicle_registration_number","driver_license_expires_at"},
     * @OA\Property(property="user_id", type="integer", example=1),
     * @OA\Property(property="vehicle_make", type="string", example="Ford"),
     * @OA\Property(property="vehicle_model", type="string", example="Transit Custom"),
     * @OA\Property(property="vehicle_colour", type="string", example="Snow"),
     * @OA\Property(property="vehicle_registration_number", type="string", example="ND 409-717"),
     * @OA\Property(property="driver_license_expires_at", type="string", format="date", example="2030-08-16")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Driver created successfully",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=6),
     * @OA\Property(property="full_name", type="string", nullable=true, example="Gideon Allie"),
     * @OA\Property(property="city", type="string", nullable=true, example="Durban"),
     * @OA\Property(property="vehicle_make", type="string", example="Ford"),
     * @OA\Property(property="vehicle_model", type="string", example="Transit Custom"),
     * @OA\Property(property="vehicle_colour", type="string", example="Snow"),
     * @OA\Property(property="vehicle_registration_number", type="string", example="ND 409-717"),
     * @OA\Property(property="driver_license_expires_at", type="string", example="2030-08-16")
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The user id field is required. (and 5 more errors)"),
     * @OA\Property(
     * property="errors",
     * type="object",
     * @OA\Property(property="user_id", type="array", @OA\Items(type="string", example="The user id field is required.")),
     * @OA\Property(property="vehicle_make", type="array", @OA\Items(type="string", example="The vehicle make field is required.")),
     * @OA\Property(property="vehicle_model", type="array", @OA\Items(type="string", example="The vehicle model field is required.")),
     * @OA\Property(property="vehicle_colour", type="array", @OA\Items(type="string", example="The vehicle colour field is required.")),
     * @OA\Property(property="vehicle_registration_number", type="array", @OA\Items(type="string", example="The vehicle registration number field is required.")),
     * @OA\Property(property="driver_license_expires_at", type="array", @OA\Items(type="string", example="The driver license expires at field is required."))
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to create a driver."
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The user with the specified ID was not found."
     * )
     * )
     */
    public function store(DriverRequest $request)
    {
        $driver = Driver::create($request->validated());

        return new DriverResource($driver->refresh());
    }

    /**
     * @OA\Get(
     * path="/api/v1/driver/{id}",
     * summary="Get a single driver by ID",
     * tags={"Drivers"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the driver to retrieve",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=6),
     * @OA\Property(property="full_name", type="string", nullable=true, example="Gideon Allie"),
     * @OA\Property(property="city", type="string", nullable=true, example="Durban"),
     * @OA\Property(property="vehicle_make", type="string", example="Ford"),
     * @OA\Property(property="vehicle_model", type="string", example="Transit Custom"),
     * @OA\Property(property="vehicle_colour", type="string", example="Snow"),
     * @OA\Property(property="vehicle_registration_number", type="string", example="ND 409-717"),
     * @OA\Property(property="driver_license_expires_at", type="string", format="date", example="2030-08-16")
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to view this driver."
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The driver with the specified ID was not found."
     * )
     * )
     */
    public function show(Driver $driver)
    {
        Gate::authorize('view', $driver);

        return new DriverResource($driver);
    }


    /**
     * @OA\Put(
     * path="/api/v1/driver/{id}",
     * summary="Update an existing driver",
     * tags={"Drivers"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the driver to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="user_id", type="integer", example=6),
     * @OA\Property(property="vehicle_make", type="string", example="Ford"),
     * @OA\Property(property="vehicle_model", type="string", example="Transit Custom"),
     * @OA\Property(property="vehicle_colour", type="string", example="Snow"),
     * @OA\Property(property="vehicle_registration_number", type="string", example="ND 409-717"),
     * @OA\Property(property="driver_license_expires_at", type="string", format="date", example="2030-08-16")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Driver updated successfully",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=6),
     * @OA\Property(property="full_name", type="string", nullable=true, example="Gideon Allie"),
     * @OA\Property(property="city", type="string", nullable=true, example="Durban"),
     * @OA\Property(property="vehicle_make", type="string", example="Ford"),
     * @OA\Property(property="vehicle_model", type="string", example="Transit Custom"),
     * @OA\Property(property="vehicle_colour", type="string", example="Snow"),
     * @OA\Property(property="vehicle_registration_number", type="string", example="ND 409-717"),
     * @OA\Property(property="driver_license_expires_at", type="string", format="date", example="2030-08-16")
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The user id field is required. (and 5 more errors)"),
     * @OA\Property(
     * property="errors",
     * type="object",
     * @OA\Property(property="user_id", type="array", @OA\Items(type="string", example="The user id field is required.")),
     * @OA\Property(property="vehicle_make", type="array", @OA\Items(type="string", example="The vehicle make field is required.")),
     * @OA\Property(property="vehicle_model", type="array", @OA\Items(type="string", example="The vehicle model field is required.")),
     * @OA\Property(property="vehicle_colour", type="array", @OA\Items(type="string", example="The vehicle colour field is required.")),
     * @OA\Property(property="vehicle_registration_number", type="array", @OA\Items(type="string", example="The vehicle registration number field is required.")),
     * @OA\Property(property="driver_license_expires_at", type="array", @OA\Items(type="string", example="The driver license expires at field is required."))
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to update this driver."
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The driver with the specified ID was not found."
     * )
     * )
     */
    public function update(DriverRequest $request, Driver $driver)
    {
        $driver->update($request->validated());

        return new DriverResource($driver->refresh());
    }

    /**
     * @OA\Delete(
     * path="/api/v1/driver/{id}",
     * summary="Delete a driver",
     * tags={"Drivers"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the driver to delete",
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\Response(
     * response=200,
     * description="Driver deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Driver deleted successfully")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The driver with the specified ID was not found."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to delete this driver."
     * )
     * )
     */
    public function destroy(Driver $driver)
    {
        Gate::authorize('delete', $driver);

        $driver->delete();

        return response()->json([
            'message' => 'Driver deleted successfully'
        ]);
    }
}
