<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartnershipRequest;
use App\Http\Requests\UpdatedPartnershipRequest;
use App\Models\Partnership;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PartnershipController extends Controller
{
    Use ApiResponse;
    public function index(): JsonResponse
    {
         try {
            $partnerships = Partnership::orderBy('created_at', 'asc')->get();
            $partnerships->transform(function ($partnerships) {
            $partnerships->logo_url = $partnerships->logo ? asset('storage/' . $partnerships->logo) : null;
                return $partnerships;
            });
            return $this->successResponse($partnerships);
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
    public function store(StorePartnershipRequest $request): JsonResponse
    {
        try {
            $newPartnership = DB::transaction(function () use ($request) {
                $validated = $request->validated();
                if ($request->hasFile('logo')) {
                    $logoPath = $request->file('logo')->store('logos', 'public');
                    $validated['logo'] = $logoPath;
                }
                return Partnership::create($validated);
            });
            return $this->successResponse($newPartnership, 'Partnership created successfully.', 201);
        } catch(\Exception $e) {
            return $this->errorResponse('Failed to create partnership.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id):  JsonResponse
    {
        try {
            $partnership = Partnership::findOrFail($id);
            $partnership->logo_url = $partnership->logo ? asset('storage/' . $partnership->logo) : null;
            return $this->successResponse($partnership, 'Partnership retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Partnership not found.', 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partnership $partnership)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedPartnershipRequest $request, Partnership $partnership): JsonResponse
    {
        try {
            $updatedPartnership = DB::transaction(function () use ($request, $partnership) {
                $validated = $request->validated();
                if ($request->hasFile('logo')) {
                    $logoPath = $request->file('logo')->store('logos', 'public');
                    $validated['logo'] = $logoPath;
                }
                $partnership->update($validated);
                return $partnership;
            });
            return $this->successResponse($updatedPartnership, 'Partnership updated successfully.');
        } catch(\Exception $e) {
            return $this->errorResponse('Failed to update partnership.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partnership $partnership): JsonResponse
    {
        try {
            DB::transaction(function () use ($partnership) {
                if ($partnership->logo && Storage::disk('public')->exists($partnership->logo)) {
                    Storage::disk('public')->delete($partnership->logo);
                }
                $partnership->delete();
            });
            return $this->successResponse(null, 'Partnership deleted successfully.', 204);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete partnership.', 500);
            }
    }

}
