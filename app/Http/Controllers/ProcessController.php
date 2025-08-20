<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProcessRequest;
use App\Http\Requests\UpdatedProcessRequest;
use App\Models\Process;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProcessController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $processes = Process::orderBy('created_at', 'asc')->get();
            $processes->transform(function ($process) {
                $process->icon_url = $process->icon ? asset('storage/' . $process->icon) : null;
                return $process;
            });

            return $this->successResponse($processes);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function create()
    {
        //
    }

    public function store(StoreProcessRequest $request): JsonResponse
    {
        try {
            $newProcess = DB::transaction(function () use ($request) {
                $validated = $request->validated();

                if ($request->hasFile('icon')) {
                    $iconPath = $request->file('icon')->store('icons', 'public');
                    $validated['icon'] = $iconPath;
                }

                return Process::create($validated);
            });

            return $this->successResponse($newProcess, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create Process.', 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $process = Process::findOrFail($id);
            $process->icon_url = $process->icon ? asset('storage/' . $process->icon) : null;

            return $this->successResponse($process);
        } catch (\Exception $e) {
            return $this->errorResponse('Process not found.', 404);
        }
    }

    public function edit(Process $process)
    {
        //
    }

    public function update(UpdatedProcessRequest $request, $id): JsonResponse
    {
        try {
            $updatedProcess = DB::transaction(function () use ($request, $id) {
                $validated = $request->validated();
                $process = Process::findOrFail($id);

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
                return $process;
            });

            return $this->successResponse($updatedProcess, 'Process updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update Process: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $deleted = DB::transaction(function () use ($id) {
                $process = Process::findOrFail($id);
                return $process->delete();
            });

            return $this->successResponse($id, 'Process deleted successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete Process: ' . $e->getMessage(), 500);
        }
    }
}
