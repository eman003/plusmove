<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageRequest;
use App\Http\Resources\V1\PackageResource;
use App\Models\V1\Package;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        return new PackageResource($package->loadMissing('customer', 'driver', 'address'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PackageRequest $request, Package $package)
    {
        $package->update($request->validated());

        return new PackageResource($package->loadMissing('customer', 'driver', 'address'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        $package->delete();

        return response()->json([
            'message' => 'Package deleted successfully'
        ]);
    }
}
