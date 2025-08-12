<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePortofolioCategoryRequest;
use App\Http\Requests\UpdatedPortofolioCategoryRequest;
use App\Models\PortofolioCategory;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PortofolioCategoryController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $categories = PortofolioCategory::orderByDesc('created_at')->get();
            return $this->successResponse($categories);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePortofolioCategoryRequest $request): JsonResponse
    {
        try {
            $newCategory = DB::transaction(function () use ($request) {
                $validated = $request->validated();
                $validated['slug'] = Str::slug($validated['name']);

                return PortofolioCategory::create($validated);
            });

            return $this->successResponse($newCategory, 'Category created successfully.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create category.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $category = PortofolioCategory::findOrFail($id);
            return $this->successResponse($category);
        } catch (\Exception $e) {
            return $this->errorResponse('Category not found.', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedPortofolioCategoryRequest $request, $id): JsonResponse
    {
        try {
            $updatedCategory = DB::transaction(function () use ($request, $id) {
                $validated = $request->validated();
                $validated['slug'] = Str::slug($validated['name']);

                $category = PortofolioCategory::findOrFail($id);
                $category->update($validated);

                return $category;
            });

            return $this->successResponse($updatedCategory, 'Category updated successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update category.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            DB::transaction(function () use ($id) {
                $category = PortofolioCategory::findOrFail($id);
                $category->delete();
            });

            return $this->successResponse(null, 'Category deleted successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete category.', 500);
        }
    }
}
