<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceLandingPageRequest;
use App\Http\Requests\UpdatedServiceLandingPageRequest;
use App\Models\ServiceLandingPage;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceLandingPageController extends Controller
{
    Use ApiResponse;
    public function index(): JsonResponse   
    {
        try {
            $serviceLandingPage = ServiceLandingPage::orderByDesc('created_at', 'desc')->get();
            return $this->successResponse($serviceLandingPage);
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
    public function store(StoreServiceLandingPageRequest $request): JsonResponse
    {
        try {
            $newServiceLandingPage = DB::transaction(function () use ($request) {
                $validated = $request->validated();
                return ServiceLandingPage::create($validated);
            });
            return $this->successResponse($newServiceLandingPage, 'Service Landing Page created successfully.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create Service Landing Page.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $serviceLandingPage = ServiceLandingPage::findOrFail($id);
            return $this->successResponse($serviceLandingPage);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceLandingPage $serviceLandingPage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedServiceLandingPageRequest $request, ServiceLandingPage $serviceLandingPage)
    {
        try {
            $updatedServiceLandingPage = DB::transaction(function () use ($request, $serviceLandingPage) {
                $validated = $request->validated();
                $serviceLandingPage->update($validated);
                return $serviceLandingPage;
            });
            return $this->successResponse($updatedServiceLandingPage, 'Service Landing Page updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update Service Landing Page.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceLandingPage $serviceLandingPage)
    {
        
    }
}
