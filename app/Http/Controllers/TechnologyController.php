<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTechnologyRequest;
use App\Http\Requests\UpdatedTechnologyRequest;
use App\Models\Technology;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TechnologyController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $technologies = Technology::orderBy('created_at', 'desc')->get();
            return $this->successResponse($technologies, 'Technologies retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve technologies.', 500);
        }
    }

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

    public function show($id): JsonResponse
    {
        try {
            $technology = Technology::findOrFail($id);
            return $this->successResponse($technology, 'Technology retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve technology.', 500);
        }
    }

    public function update(UpdatedTechnologyRequest $request, Technology $technology): JsonResponse
    {
        try {
            $updatedTechnology = DB::transaction(function () use ($request, $technology) {
                $validated = $request->validated();

                if ($request->hasFile('image')) {
                   
                    if ($technology->image && Storage::disk('public')->exists($technology->image)) {
                        Storage::disk('public')->delete($technology->image);
                    }

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
