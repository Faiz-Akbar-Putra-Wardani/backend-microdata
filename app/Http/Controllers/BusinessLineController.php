<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessLineRequest;
use App\Http\Requests\UpdatedBusinessLineRequest;
use App\Models\BusinessLine;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BusinessLineController extends Controller
{
    Use ApiResponse;
    public function index() : JsonResponse
    {
        try {
            $businessLines = BusinessLine::orderByDesc('created_at')->get();
            $businessLines->transform(function ($businessLine) {
            $businessLine->icon_url = $businessLine->icon ? asset('storage/' . $businessLine->icon) : null;
                return $businessLine;
            });
            return $this->successResponse($businessLines);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
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
    public function store(StoreBusinessLineRequest $request): JsonResponse
    {
        try {
            $newBusinessLine = DB::transaction(function () use ($request){
                $validated = $request->validated();

                if ($request->hasFile('icon')) {
                    $iconPath = $request->file('icon')->store('icons', 'public');
                    $validated['icon'] = $iconPath;
                }

                return BusinessLine::create($validated);
            });
            return $this->successResponse($newBusinessLine, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create business line.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $businessLine = BusinessLine::findOrFail($id);
            $businessLine->icon_url = $businessLine->icon ? asset('storage/' . $businessLine->icon) : null;
            return $this->successResponse($businessLine);
        } catch (\Exception $e) {
            return $this->errorResponse('Business line not found.', 404);
        }
    }
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusinessLine $businessLine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedBusinessLineRequest $request, BusinessLine $businessLine) : JsonResponse
    {
        try {
            $updatedBusinessLine = DB::transaction(function () use ($request, $businessLine) {
                $validated = $request->validated();

                if ($request->hasFile('icon')) {
                    if ($businessLine->icon && Storage::disk('public')->exists($businessLine->icon)) {
                        Storage::disk('public')->delete($businessLine->icon);
                    }
                    $iconPath = $request->file('icon')->store('icons', 'public');
                    $validated['icon'] = $iconPath;
                } else {
                    unset($validated['icon']);
                }

                $businessLine->update($validated);
                return $businessLine->fresh();
            });
            return $this->successResponse($updatedBusinessLine, 'Business line updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update business line.', 500);
        }
    }
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessLine $businessLine) : JsonResponse
    {
        try {
            DB::transaction(function () use ($businessLine) {
                if ($businessLine->icon && Storage::disk('public')->exists($businessLine->icon)) {
                    Storage::disk('public')->delete($businessLine->icon);
                }
                $businessLine->delete();
            });
            return $this->successResponse( $businessLine, 'Business line deleted successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete business line.', 500);
        }
    }
}
