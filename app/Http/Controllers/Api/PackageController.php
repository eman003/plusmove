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
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(PackageRequest $request)
    {
        $package = Package::create($request->validated());

        return new PackageResource($package->loadMissing('customer', 'driver', 'address'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        Gate::authorize('view', $package);

        return new PackageResource($package->loadMissing('customer', 'driver', 'address'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePackageRequest $request, Package $package)
    {
        if (auth()->user()->isDriver()){
            $package->update(['status' => $request->status]);
        }else{
            $package->update($request->validated());
        }

        return new PackageResource($package->loadMissing('customer', 'driver', 'address'));
    }

    /**
     * Remove the specified resource from storage.
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
