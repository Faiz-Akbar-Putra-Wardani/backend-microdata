<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePortofolioRequest;
use App\Http\Requests\UpdatePortofolioRequest;
use App\Models\Portofolio;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PortofolioController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $portfolios = Portofolio::with('category')
                ->latest()
                ->get()
                ->map(function ($portfolio) {
                    $portfolio->image_portofolio_url = $portfolio->image_portofolio
                        ? asset('storage/' . $portfolio->image_portofolio)
                        : null;
                    return $portfolio;
                });

            return $this->successResponse($portfolios, 'Portfolios retrieved successfully.', 200);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function store(StorePortofolioRequest $request): JsonResponse
    {
        try {
            $newPortfolio = DB::transaction(function () use ($request) {
                $validated = $request->validated();

                if ($request->hasFile('image_portofolio')) {
                    $validated['image_portofolio'] = $request->file('image_portofolio')
                        ->store('portfolio_images', 'public');
                }

                $portfolio = Portofolio::create($validated);

                $portfolio->load('category');
                $portfolio->image_portofolio_url = $portfolio->image_portofolio
                    ? asset('storage/' . $portfolio->image_portofolio)
                    : null;

                return $portfolio;
            });

            return $this->successResponse($newPortfolio, 'Portfolio created successfully.', 201);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $portfolio = Portofolio::with('category')->findOrFail($id);
            $portfolio->image_portofolio_url = $portfolio->image_portofolio
                ? asset('storage/' . $portfolio->image_portofolio)
                : null;

            return $this->successResponse($portfolio);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    public function update(UpdatePortofolioRequest $request, $id): JsonResponse
    {
        try {
            $updatedPortfolio = DB::transaction(function () use ($request, $id) {
                $validated = $request->validated();

                $portfolio = Portofolio::findOrFail($id);

                if ($request->hasFile('image_portofolio')) {
                    if ($portfolio->image_portofolio && Storage::disk('public')->exists($portfolio->image_portofolio)) {
                        Storage::disk('public')->delete($portfolio->image_portofolio);
                    }
                    $validated['image_portofolio'] = $request->file('image_portofolio')
                        ->store('portfolio_images', 'public');
                }

                $portfolio->update($validated);

                $portfolio->load('category');
                $portfolio->image_portofolio_url = $portfolio->image_portofolio
                    ? asset('storage/' . $portfolio->image_portofolio)
                    : null;

                return $portfolio;
            });

            return $this->successResponse($updatedPortfolio, 'Portfolio updated successfully.', 200);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            DB::transaction(function () use ($id) {
                $portfolio = Portofolio::findOrFail($id);

                if ($portfolio->image_portofolio && Storage::disk('public')->exists($portfolio->image_portofolio)) {
                    Storage::disk('public')->delete($portfolio->image_portofolio);
                }

                $portfolio->delete();
            });

            return $this->successResponse(null, 'Portfolio deleted successfully.', 200);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
