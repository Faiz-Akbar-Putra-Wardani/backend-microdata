<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatedPositionRequest;
use App\Models\Position;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{
    Use ApiResponse;
    public function index(): JsonResponse
    {
        try {
            $positions = Position::orderBy('created_at', 'asc')->get();
            return $this->successResponse($positions);
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
    public function store(StorePositionRequest $request): JsonResponse
    {
        try {
            $newPositions = DB::transaction(function () use ($request) {
                $validated = $request->validated();

                return Position::create($validated);
            });
            return $this->successResponse('Position created successfully.', 201);
        } catch (\Exception $e) {
             return $this->errorResponse('Failed to create position.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $position = Position::findOrFail($id);
            return $this->successResponse($position, 'Position retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve Position.', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedPositionRequest $request, Position $position): JsonResponse
    {
        try {
            $updatedPositions = DB::transaction(function () use ($request, $position) {
                $validated = $request->validated();

                $position->update($validated);
                return $position;
            });
            return $this->successResponse('Position updated successfully.', 200);
        } catch(\Exception $e) {
            return $this->errorResponse('Failed to updated Position', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position): JsonResponse
    {
        try {
            DB::transaction(function () use ($position){
                $position->delete();
            });
            return $this->successResponse($position, 'Position deleted successfully.', 200);
        } catch(\Exception $e) {
            return $this->errorResponse('Failed to deleted Position', 500);
        }
    }
}
