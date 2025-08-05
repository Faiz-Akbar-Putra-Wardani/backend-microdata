<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAboutUsRequest;
use App\Http\Requests\UpdatedAboutUsRequest;
use App\Models\AboutUs;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutUsController extends Controller
{
    Use ApiResponse;
    public function index() : JsonResponse
    {
        try {
            $aboutUs = AboutUs::orderByDesc('created_at', 'desc')->get();
            return $this->successResponse($aboutUs);
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
    public function store(StoreAboutUsRequest $request) : JsonResponse
    {
        try {
            $newAboutUS = DB::transaction(function () use ($request) {
                $validated = $request->validated();

                return AboutUs::create($validated);
            });

            return $this->successResponse($newAboutUS, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create About Us.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id) : JsonResponse
    {
        try {
            $aboutUs = AboutUs::findOrFail($id);
            return $this->successResponse($aboutUs);
        } catch (\Exception $e) {
            return $this->errorResponse('About Us not found.', 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AboutUs $aboutUs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedAboutUsRequest $request, AboutUs $aboutUs) : JsonResponse
    {
        try {
            $updatedAboutUs = DB::transaction(function () use ($request, $aboutUs) {
                $validated = $request->validated();
                $aboutUs->update($validated);
                return $aboutUs;
            });

            return $this->successResponse($updatedAboutUs, 'About Us updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update About Us.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AboutUs $aboutUs) : JsonResponse
    {
        try {
            DB::transaction(function () use ($aboutUs) {
                $aboutUs->delete();
            });
            return $this->successResponse($aboutUs, 'About Us deleted successfully.', 204);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete About Us.', 500);
        }
    }
}
