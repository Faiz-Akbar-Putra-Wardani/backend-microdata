<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatedPortofolioCategoryRequest;
use App\Models\PortofolioCategory;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class PortofolioCategoryController extends Controller
{
    Use ApiResponse;
    public function index(): JsonResponse
    {
        try {
            $categories = PortofolioCategory::orderByDesc('created_at', 'desc')->get();
            return $this->successResponse($categories);
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
    public function store(PortofolioCategory $request): JsonResponse
    {
        try {
            $newCategory = DB::transaction(function () use ($request) {
                $validated = $request->validated();
            });
            return $this->successResponse($newCategory, 'Category created successfully.', 201);
        }
        catch (\Exception $e) {
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
     * Show the form for editing the specified resource.
     */
    public function edit(PortofolioCategory $portofolioCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedPortofolioCategoryRequest $request, $id): JsonResponse
    {
        try {
            $updatedCategory = DB::transaction(function () use ($request, $id) {
                $validated = $request->validated();
                 $portofolioCategory = PortofolioCategory::findOrFail($id); 
                $portofolioCategory->update($validated);
                return $portofolioCategory;
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
            $deleted = DB::transaction(function () use ($id) {
                $portofolioCategory = PortofolioCategory::findOrFail($id);
                return $portofolioCategory->delete();
            });
            return $this->successResponse($id, 'Category deleted successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete category.', 500);
        }
    }
}
