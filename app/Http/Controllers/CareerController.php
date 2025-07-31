<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCareerRequest;
use App\Http\Requests\UpdatedCareerRequest;
use App\Models\Career;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class CareerController extends Controller
{
    Use ApiResponse;
    public function index(): JsonResponse
    {
        try {
            $careers = Career::orderBy('created_at', 'desc')->get();
            return $this->successResponse($careers, 'Careers retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve careers.', 500);
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
    public function store(StoreCareerRequest $request): JsonResponse
    {
        try {
            $newCareer = DB::transaction(function () use ($request) {
                $validated = $request->validated();

                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('images', 'public');
                    $validated['image'] = $imagePath;
                }

                return Career::create($validated);
            });
            return $this->successResponse($newCareer, 'Career created successfully.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create career.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $career = Career::findOrFail($id);
            return $this->successResponse($career, 'Career retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve career.', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Career $career)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedCareerRequest $request, Career $career): JsonResponse
    {
        try {
            $updatedCareer = DB::transaction(function () use ($request, $career) {
                $validated = $request->validated();

                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('images', 'public');
                    $validated['image'] = $imagePath;
                } else {
                    unset($validated['image']);
                }

                $career->update($validated);
                return $career->fresh();
            });
            return $this->successResponse($updatedCareer, 'Career updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update career.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Career $career): JsonResponse
    {
        try {
            DB::transaction(function () use ($career) {
                if ($career->image && Storage::disk('public')->exists($career->image)) {
                    Storage::disk('public')->delete($career->image);
                }
                $career->delete();
            });
            return $this->successResponse( $career, 'Career deleted successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete career.', 500);
        }
    }
}
