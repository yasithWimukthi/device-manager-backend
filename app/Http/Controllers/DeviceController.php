<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * get all devices
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $devices = Device::all();
            return response()->json(['message' => 'Devices retrieved successfully', 'data' => $devices], 200);
        }catch (\Exception $e){
            return response()->json(['message' => 'Devices retrieval failed', 'data' => $e->getMessage()], 500);
        }
    }

    /**
     * store device details
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'number' => 'required|integer|unique:devices',
                'type' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Allow only image files
                'date_created' => 'required|date',
                'status' => 'required|in:active,inactive',
            ]);

            $imagePath = $validatedData['image']->store('images', 'public');

            $device = Device::create([
                'number' => $validatedData['number'],
                'type' => $validatedData['type'],
                'image' => $imagePath,
                'date_created' => $validatedData['date_created'],
                'status' => $validatedData['status'],
            ]);

            return response()->json(['message' => 'Device created successfully', 'data' => $device], 201);
        }catch (\Exception $e){
            return response()->json(['message' => 'Device creation failed', 'data' => $e->getMessage()], 500);
        }

    }
}
