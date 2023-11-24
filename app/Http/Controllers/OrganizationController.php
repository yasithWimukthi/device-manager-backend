<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
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

            return response()->json(['message' => 'Organization created successfully', 'data' => $organization], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Organization creation failed', 'data' => $e->getMessage()], 500);
        }
    }
}
