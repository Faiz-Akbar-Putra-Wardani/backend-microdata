<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMicrodataOptionsRequest;
use App\Http\Requests\UpdatedMicrodataOptionsRequest;
use App\Models\MicrodataOption;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MicrodataOptionController extends Controller
{
    Use ApiResponse;
    public function index(): JsonResponse
    {
        try {
            $microdataOptions = MicrodataOption::orderByDesc('created_at', 'desc')->get();
            return $this->successResponse($microdataOptions);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMicrodataOptionsRequest $request): JsonResponse
    {
        try {
            $newMicrodataOption = DB::transaction(function () use ($request) {
                $validated = $request->validated();

                return MicrodataOption::create($validated);
            });
            return $this->successResponse('Microdata option created successfully.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create microdata option.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $microdataOption = MicrodataOption::findOrFail($id);
            return $this->successResponse($microdataOption, 'Microdata option retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve microdata option.', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MicrodataOption $microdataOption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedMicrodataOptionsRequest $request, MicrodataOption $microdataOption): JsonResponse
    {
        try {
            $updatedMicrodataOption = DB::transaction(function () use ($request, $microdataOption) {
                $validated = $request->validated();
                $microdataOption->update($validated);
                return $microdataOption;
            });
            return $this->successResponse($updatedMicrodataOption, 'Microdata option updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update microdata option.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MicrodataOption $microdataOption): JsonResponse
    {
        try {
            DB::transaction(function () use ($microdataOption) {
                $microdataOption->delete();
            });
            return $this->successResponse('Microdata option deleted successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete microdata option.', 500);
        }
    }
}
