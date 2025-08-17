<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Http\Resources\V1\PackageResource;
use App\Models\V1\Package;
use Illuminate\Support\Facades\Gate;

class PackageController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/v1/package",
     * summary="Get a list of packages",
     * tags={"Packages"},
     * security={{"sanctum":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=174),
     * @OA\Property(property="customer", type="string", example="Joseph Ltd"),
     * @OA\Property(property="status", type="integer", example=4),
     * @OA\Property(property="tracking_number", type="string", example="329FE7BA-C64A-4360-A604-EFF5935B0E18"),
     * @OA\Property(property="delivery_note", type="string", example="Voluptatem asperiores culpa sunt et."),
     * @OA\Property(property="delivered_at", type="string", nullable=true, example="2025-08-15"),
     * @OA\Property(property="cancelled_at", type="string", nullable=true, example=null),
     * @OA\Property(
     * property="delivery_address",
     * type="object",
     * @OA\Property(property="id", type="integer", example=79),
     * @OA\Property(property="name", type="string", nullable=true, example="Work"),
     * @OA\Property(property="address_line_1", type="string", example="692 Phoenix Zulu Residences"),
     * @OA\Property(property="address_line_2", type="string", nullable=true, example="3706 Thandi Club Drive"),
     * @OA\Property(property="suburb", type="string", example="Waverley"),
     * @OA\Property(property="city", type="string", example="Kimberley"),
     * @OA\Property(property="province", type="string", example="Limpopo"),
     * @OA\Property(property="postal_code", type="string", example="4942"),
     * @OA\Property(property="country", type="string", example="South Africa"),
     * @OA\Property(property="created_at", type="string", example="23 hours ago")
     * )
     * )
     * ),
     * @OA\Property(
     * property="links",
     * type="object",
     * @OA\Property(property="first", type="string", nullable=true, example="http://plusmove.test/api/v1/package?page=1"),
     * @OA\Property(property="last", type="string", nullable=true, example="http://plusmove.test/api/v1/package?page=12"),
     * @OA\Property(property="prev", type="string", nullable=true),
     * @OA\Property(property="next", type="string", nullable=true, example="http://plusmove.test/api/v1/package?page=2")
     * ),
     * @OA\Property(
     * property="meta",
     * type="object",
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="from", type="integer", nullable=true, example=1),
     * @OA\Property(property="last_page", type="integer", example=12),
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
     * @OA\Property(property="path", type="string", example="http://plusmove.test/api/v1/package"),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="to", type="integer", nullable=true, example=15),
     * @OA\Property(property="total", type="integer", example=173)
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to view the packages."
     * )
     * )
     */
    public function index()
    {
        Gate::authorize('viewAny', Package::class);

        $packages = Package::with('customer', 'driver', 'address')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return PackageResource::collection($packages);
    }

    /**
     * @OA\Post(
     * path="/api/v1/package",
     * summary="Create a new package",
     * tags={"Packages"},
     * security={{"sanctum":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"customer_id", "address_id", "scheduled_for"},
     * @OA\Property(property="customer_id", type="integer", example=1),
     * @OA\Property(property="address_id", type="integer", example=1),
     * @OA\Property(property="scheduled_for", type="string", format="date", example="2025-08-13"),
     * @OA\Property(property="delivery_note", type="string", nullable=true, example="Optional delivery notes"),
     * @OA\Property(property="delivered_at", type="string", format="date", nullable=true),
     * @OA\Property(property="cancelled_at", type="string", format="date", nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Package created successfully",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=2),
     * @OA\Property(property="customer", type="string", example="Mashigo Ltd"),
     * @OA\Property(property="status", type="integer", example=3),
     * @OA\Property(property="tracking_number", type="string", example="CD6FF301-5E01-4E5F-AE57-751070462B1C"),
     * @OA\Property(property="delivery_note", type="string", example="Fuga placeat qui sint."),
     * @OA\Property(property="delivered_at", type="string", nullable=true, example="2025-08-13"),
     * @OA\Property(property="cancelled_at", type="string", nullable=true),
     * @OA\Property(
     * property="delivery_address",
     * type="object",
     * @OA\Property(property="id", type="integer", example=63),
     * @OA\Property(property="name", type="string", nullable=true, example="School"),
     * @OA\Property(property="address_line_1", type="string", example="4909 Willow Lane Gardens"),
     * @OA\Property(property="address_line_2", type="string", nullable=true, example="9138 De Lange Landing Road"),
     * @OA\Property(property="suburb", type="string", example="Mangaung"),
     * @OA\Property(property="city", type="string", example="East London"),
     * @OA\Property(property="province", type="string", example="North West"),
     * @OA\Property(property="postal_code", type="string", example="7429"),
     * @OA\Property(property="country", type="string", example="South Africa"),
     * @OA\Property(property="created_at", type="string", example="23 hours ago")
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The customer id field is required. (and 2 more errors)"),
     * @OA\Property(
     * property="errors",
     * type="object",
     * @OA\Property(property="customer_id", type="array", @OA\Items(type="string", example="The customer id field is required.")),
     * @OA\Property(property="address_id", type="array", @OA\Items(type="string", example="The address id field is required.")),
     * @OA\Property(property="scheduled_for", type="array", @OA\Items(type="string", example="The scheduled for field is required."))
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to create a package."
     * )
     * )
     */
    public function store(PackageRequest $request)
    {
        $package = Package::create($request->validated());

        return new PackageResource($package->loadMissing('customer', 'driver', 'address'));
    }

    /**
     * @OA\Get(
     * path="/api/v1/package/{id}",
     * summary="Get a single package by ID",
     * tags={"Packages"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the package to retrieve",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=2),
     * @OA\Property(property="customer", type="string", example="Mashigo Ltd"),
     * @OA\Property(property="status", type="integer", example=3),
     * @OA\Property(property="tracking_number", type="string", example="CD6FF301-5E01-4E5F-AE57-751070462B1C"),
     * @OA\Property(property="delivery_note", type="string", example="Fuga placeat qui sint."),
     * @OA\Property(property="delivered_at", type="string", format="date", nullable=true, example="2025-08-13"),
     * @OA\Property(property="cancelled_at", type="string", format="date", nullable=true, example=null),
     * @OA\Property(
     * property="delivery_address",
     * type="object",
     * @OA\Property(property="id", type="integer", example=63),
     * @OA\Property(property="name", type="string", nullable=true, example="School"),
     * @OA\Property(property="address_line_1", type="string", example="4909 Willow Lane Gardens"),
     * @OA\Property(property="address_line_2", type="string", nullable=true, example="9138 De Lange Landing Road"),
     * @OA\Property(property="suburb", type="string", example="Mangaung"),
     * @OA\Property(property="city", type="string", example="East London"),
     * @OA\Property(property="province", type="string", example="North West"),
     * @OA\Property(property="postal_code", type="string", example="7429"),
     * @OA\Property(property="country", type="string", example="South Africa"),
     * @OA\Property(property="created_at", type="string", example="23 hours ago")
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to view this package."
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The package with the specified ID was not found."
     * )
     * )
     */
    public function show(Package $package)
    {
        Gate::authorize('view', $package);

        return new PackageResource($package->loadMissing('customer', 'driver', 'address'));
    }

    /**
     * @OA\Put(
     * path="/api/v1/package/{id}",
     * summary="Update an existing package",
     * tags={"Packages"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the package to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="address_id", type="integer", example=63),
     * @OA\Property(property="status", type="integer", example=3),
     * @OA\Property(property="delivery_note", type="string", nullable=true),
     * @OA\Property(property="delivered_at", type="string", format="date", nullable=true),
     * @OA\Property(property="cancelled_at", type="string", format="date", nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Package updated successfully",
     * @OA\JsonContent(
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="id", type="integer", example=2),
     * @OA\Property(property="customer", type="string", example="Mashigo Ltd"),
     * @OA\Property(property="status", type="integer", example=3),
     * @OA\Property(property="tracking_number", type="string", example="CD6FF301-5E01-4E5F-AE57-751070462B1C"),
     * @OA\Property(property="delivery_note", type="string", example="Fuga placeat qui sint."),
     * @OA\Property(property="delivered_at", type="string", nullable=true, example="2025-08-13"),
     * @OA\Property(property="cancelled_at", type="string", nullable=true),
     * @OA\Property(
     * property="delivery_address",
     * type="object",
     * @OA\Property(property="id", type="integer", example=63),
     * @OA\Property(property="name", type="string", nullable=true, example="School"),
     * @OA\Property(property="address_line_1", type="string", example="4909 Willow Lane Gardens"),
     * @OA\Property(property="address_line_2", type="string", nullable=true, example="9138 De Lange Landing Road"),
     * @OA\Property(property="suburb", type="string", example="Mangaung"),
     * @OA\Property(property="city", type="string", example="East London"),
     * @OA\Property(property="province", type="string", example="North West"),
     * @OA\Property(property="postal_code", type="string", example="7429"),
     * @OA\Property(property="country", type="string", example="South Africa"),
     * @OA\Property(property="created_at", type="string", example="23 hours ago")
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation errors",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The address id field is required. (and 1 more error)"),
     * @OA\Property(
     * property="errors",
     * type="object",
     * @OA\Property(property="address_id", type="array", @OA\Items(type="string", example="The address id field is required.")),
     * @OA\Property(property="status", type="array", @OA\Items(type="string", example="The status field is required."))
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to update this package."
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The package with the specified ID was not found."
     * )
     * )
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        // A driver is only allowed to update the status of a package
        if (auth()->user()->isDriver()){
            $package->update(['status' => $request->status]);
        }else{
            $package->update($request->validated());
        }

        return new PackageResource($package->loadMissing('customer', 'driver', 'address'));
    }

    /**
     * @OA\Delete(
     * path="/api/v1/package/{id}",
     * summary="Delete a package",
     * tags={"Packages"},
     * security={{"sanctum":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the package to delete",
     * @OA\Schema(type="integer", format="int64")
     * ),
     * @OA\Response(
     * response=200,
     * description="Package deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Package deleted successfully")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - The package with the specified ID was not found."
     * ),
     * @OA\Response(
     * response=403,
     * description="Forbidden - The user does not have permission to delete this package."
     * )
     * )
     */
    public function destroy(Package $package)
    {
        Gate::authorize('delete', $package);

        $package->delete();

        return response()->json([
            'message' => 'Package deleted successfully'
        ]);
    }
}
