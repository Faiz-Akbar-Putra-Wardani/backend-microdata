<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProcessRequest;
use App\Http\Requests\UpdatedServiceRequest;
use App\Models\Process;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProcessController extends Controller
{
    Use ApiResponse;
    public function index(): JsonResponse
    {
        try {
            $Process = Process::orderByDesc('created_at', 'desc')->get();
            $Process->transform(function ($process) {
                $process->icon_url = $process->icon ? asset('storage/' . $process->icon) : null;
                return $process;
            });

            return $this->successResponse($Process);
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
    public function store(StoreProcessRequest $request): JsonResponse
    {
        try {
            $newProcess = DB::transaction(function () use ($request){
                $validated = $request->validated();

                if ($request->hasFile('icon')) {
                    $iconPath = $request->file('icon')->store('icons', 'public');
                    $validated['icon'] = $iconPath;
                }
                return Process::created($validated);
            });
            return $this->successResponse($newProcess, 'Process created successfully.', 201);
        } catch (\Exception $e){
            return $this->errorResponse('Failed to create team.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $process = Process::findOrFail($id);
            $process->icon_url = $process->icon ? asset('storage/' . $process->icon) : null;

         return $this->successResponse($process, 'Process retrieved successfully.');
        } catch (\Exception $e) {
              return $this->errorResponse('Failed to retrieve process.', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Process $process)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedServiceRequest $request, Process $process): JsonResponse
    {
        try {
            $updatedProcess = DB::transaction(function () use ($request, $process){
                $validated = $request->validated();

               if ($request->hasFile('icon')) {
                    if ($process->icon && Storage::disk('public')->exists($process->icon)) {
                        Storage::disk('public')->delete($process->icon);
                    }

                    $iconPath = $request->file('icon')->store('icons', 'public');
                    $validated['icon'] = $iconPath;
                } else {
                    unset($validated['icon']);
                }

                $process->update($validated);
                return $process->fresh();
            });
            return $this->successResponse($process, 'Process updated successfully.', 200);
        } catch(\Exception $e) {
            return $this->errorResponse('Failed to updated process', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Process $process): JsonResponse
    {
        try {
            DB::transaction(function () use ($process){
                $process->delete();
            });
            return $this->successResponse($process, 'Process deleted successfully.', 200);
        } catch (\Exception $e) {
             return $this->errorResponse('Failed tp deleted Process.', 500);
        }
    }
}
