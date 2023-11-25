<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{

    /**
     * get all organizations
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        try {
            // eager load locations and their devices
            $organizations = Organization::with('locations.devices')->get();
            return response()->json(['message' => 'Organizations retrieved successfully', 'data' => $organizations], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Organizations retrieval failed', 'data' => $e->getMessage()], 500);
        }
    }

    /**
     * store organization details
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required|string|unique:organizations',
                'name' => 'required|string',
                'locations' => 'array|max:5', // Restrict to at most 10 locations
            ]);

            $organization = Organization::create($validatedData);
            // add locations to organization
            $organization->locations()->attach($request->locations);
            $organizations = Organization::with('locations.devices')->get();
            return response()->json(['message' => 'Organization created successfully', 'data' => $organizations], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Organization creation failed', 'data' => $e->getMessage()], 500);
        }
    }
}
