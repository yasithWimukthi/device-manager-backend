<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLocationRequest;
use App\Models\Device;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function store(CreateLocationRequest $request)
    {
        try {
            $location = Location::create($request->validated());
            // add devices to location
            $devices = $request->devices;
            foreach ($devices as $device) {
                // find device
                $device = Device::find($device);
                // update device location
                $device->location_id = $location->id;
                $device->save();
            }

            return response()->json(['message' => 'Location created successfully', 'data' => $location], 201);
        }catch (\Exception $e){
            return response()->json(['message' => 'Location creation failed', 'data' => $e->getMessage()], 500);
        }

    }
}
