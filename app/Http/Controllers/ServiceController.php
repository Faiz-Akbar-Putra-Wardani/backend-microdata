<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdatedServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Service;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    use ApiResponse;
    public function index(): JsonResponse
    {
        try {
            $services = Service::orderBy('created_at', 'desc')->get();
            return $this->successResponse($services, 'Services retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve services.', 500);
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
    public function store(StoreServiceRequest $request): JsonResponse
    {
      try {
        $newService = DB::transaction(function () use ($request) {
            $validated = $request->validated();

            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('icons', 'public');
                $validated['icon'] = $iconPath;
            }

            return Service::create($validated);
        });
        return $this->successResponse($newService, 'Service created successfully.', 201);
      } catch (\Exception $e) {
        return $this->errorResponse('Failed to create service.', 500);
      }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $service = Service::findOrFail($id);
            return $this->successResponse($service, 'Service retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve service.', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedServiceRequest $request, Service $service): JsonResponse
    {
    try {
        $updatedService = DB::transaction(function () use ($request, $service) {
            $validated = $request->validated();

            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('icons', 'public');
                $validated['icon'] = $iconPath;
            } else {
                unset($validated['icon']); 
            }

            $service->update($validated);

            return $service->fresh(); 
        });

        return $this->successResponse($updatedService, 'Service updated successfully.', 200);
    } catch (\Exception $e) {
        return $this->errorResponse('Failed to update service.', 500);
    }
   }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service): JsonResponse
    {
       try {
        DB::transaction(function () use ($service){
            if ($service->icon && Storage::disk('public')->exists($service->icon)) {
                Storage::disk('public')->delete($service->icon);
            }
            $service->delete();
        });
         return $this->successResponse($service, 'Service deleted successfully.', 200);
       } catch (\Exception $e) {
           return $this->errorResponse('Failed to delete service.', 500);
       }
   }
}
