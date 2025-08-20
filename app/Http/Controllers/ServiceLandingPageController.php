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
            $serviceLandingPage = ServiceLandingPage::orderBy('created_at', 'asc')->get();
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
    public function update(UpdatedServiceLandingPageRequest $request, $id): JsonResponse
    {
        try {
            $updatedServiceLandingPage = DB::transaction(function () use ($request, $id) {
                $validated = $request->validated();

                $serviceLandingPage = ServiceLandingPage::findOrFail($id);
                $serviceLandingPage->update($validated);

                return $serviceLandingPage;
            });

            return $this->successResponse($updatedServiceLandingPage, 'Service Landing Page updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update Service Landing Page: ' . $e->getMessage(), 500);
        }
   }

    public function destroy($id): JsonResponse
    {
        try {
            $deleted = DB::transaction(function () use ($id) {

                $serviceLandingPage = ServiceLandingPage::findOrFail($id);
                return $serviceLandingPage->delete();
            });

            return $this->successResponse($id, 'Service Landing Page deleted successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete Service Landing Page: ' . $e->getMessage(), 500);
        }
    }

}
