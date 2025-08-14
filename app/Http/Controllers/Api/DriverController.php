<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DriverRequest;
use App\Http\Resources\V1\DriverResource;
use App\Models\V1\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::latest('id')->paginate(15)->withQueryString();

        return DriverResource::collection($drivers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DriverRequest $request)
    {
        $driver = Driver::create($request->validated());

        return new DriverResource($driver->refresh());
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        return new DriverResource($driver);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DriverRequest $request, Driver $driver)
    {
        $driver->update($request->validated());

        return new DriverResource($driver->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();

        return response()->json([
            'message' => 'Driver deleted successfully'
        ]);
    }
}
