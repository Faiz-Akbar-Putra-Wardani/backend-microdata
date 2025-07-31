<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTechnologyRequest;
use App\Http\Requests\UpdatedTechnologyRequest;
use App\Models\Technology;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TechnologyController extends Controller
{
   Use ApiResponse;
    public function index(): JsonResponse
    {
        try {
            $technologies = Technology::orderBy('created_at', 'desc')->get();
            return $this->successResponse($technologies, 'Technologies retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve technologies.', 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTechnologyRequest $request): JsonResponse
    {
        try {
            $newTechnology = DB::transaction(function () use ($request) {
                $validated = $request->validated();

                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('images', 'public');
                    $validated['image'] = $imagePath;
                } 
                return Technology::create($validated);
            });
            return $this->successResponse($newTechnology, 'Technology created successfully.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create technology.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $technology = Technology::findOrFail($id);
            return $this->successResponse($technology, 'Technology retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve technology.', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Technology $technology)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedTechnologyRequest $request, Technology $technology): JsonResponse
    {
        try {
            $updatedTechnology = DB::transaction(function () use ($request, $technology) {
                $validated = $request->validated();

                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('images', 'public');
                    $validated['image'] = $imagePath;
                } else {
                    unset($validated['image']);
                }
                 $technology->update($validated);
                 return $technology->fresh();
            });
            return $this->successResponse($updatedTechnology, 'Technology updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update technology.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Technology $technology): JsonResponse
    {
        try {
        DB::transaction(function () use ($technology) {
            
            if ($technology->image && Storage::disk('public')->exists($technology->image)) {
                Storage::disk('public')->delete($technology->image);
            }
            $technology->delete();
        });

        return $this->successResponse(null, 'Technology deleted successfully.', 204);
    } catch (\Exception $e) {
        return $this->errorResponse('Failed to delete technology.', 500);
    }
  }
}
