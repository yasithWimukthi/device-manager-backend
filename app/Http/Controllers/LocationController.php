<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLocationRequest;
use App\Models\Device;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * get all locations
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // eager load devices
            $locations = Location::with('devices')->get();
            return response()->json(['message' => 'Locations retrieved successfully', 'data' => $locations], 200);
        }catch (\Exception $e){
            return response()->json(['message' => 'Locations retrieval failed', 'data' => $e->getMessage()], 500);
        }
    }

    /**
     * store location details
     * @param CreateLocationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            $locations = Location::with('devices')->get();
            return response()->json(['message' => 'Location created successfully', 'data' => $locations], 201);
        }catch (\Exception $e){
            return response()->json(['message' => 'Location creation failed', 'data' => $e->getMessage()], 500);
        }
    }

    public function addDevices(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'location_id' => 'required|integer|exists:locations,id',
                'devices' => 'required|array|max:10', // Restrict to at most 10 devices
                'devices.*' => 'exists:devices,id', // Ensure each device exists
            ]);

            $location = Location::find($validatedData['location_id']);
            $devices = $validatedData['devices'];

            foreach ($devices as $deviceId) {
                // Find the device
                $device = Device::find($deviceId);
                if ($device) {
                    // Update device location
                    $device->location_id = $validatedData['location_id'];
                    $device->save();
                } else {
                    \Log::error("Device not found for ID: $deviceId");
                }
            }
            $locations = Location::with('devices')->get();
            return response()->json(['message' => 'Devices added successfully', 'data' => $locations], 200);
        }catch (\Exception $e){
            return response()->json(['message' => 'Devices addition failed', 'data' => $e->getMessage()], 500);
        }
    }

}
