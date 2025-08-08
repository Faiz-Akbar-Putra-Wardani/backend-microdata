<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePortfolioRequest;
use App\Http\Requests\StorePortofolioRequest;
use App\Http\Requests\UpdatePortfolioRequest;
use App\Http\Requests\UpdatePortofolioRequest;
use App\Models\Portfolio;
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
                ->orderBy('created_at', 'desc')
                ->get();

            $portfolios->transform(function ($portfolio) {
                $portfolio->image_url = $portfolio->image ? asset('storage/' . $portfolio->image) : null;
                $portfolio->image_portfolio_url = $portfolio->image_portofolio ? asset('storage/' . $portfolio->image_portofolio) : null;
                return $portfolio;
            });

            return $this->successResponse($portfolios);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function store(StorePortofolioRequest $request): JsonResponse
    {
        try {
            $newPortfolio = DB::transaction(function () use ($request) {
                $validated = $request->validated();

                if ($request->hasFile('image')) {
                    $validated['image'] = $request->file('image')->store('portfolios', 'public');
                }
                if ($request->hasFile('image_portofolio')) {
                    $validated['image_portofolio'] = $request->file('image_portofolio')->store('portfolio_images', 'public');
                }

                return Portofolio::create($validated);
            });

            return $this->successResponse($newPortfolio, 'Portfolio created successfully.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create portfolio.', 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $portfolio = Portofolio::with('category')->findOrFail($id);
            $portfolio->image_url = $portfolio->image ? asset('storage/' . $portfolio->image) : null;
            $portfolio->image_portfolio_url = $portfolio->image_portofolio ? asset('storage/' . $portfolio->image_portofolio) : null;

            return $this->successResponse($portfolio, 'Portfolio retrieved successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Portfolio not found.', 404);
        }
    }

    public function update(UpdatePortofolioRequest $request, Portofolio $portfolio): JsonResponse
    {
        try {
            $updatedPortfolio = DB::transaction(function () use ($request, $portfolio) {
                $validated = $request->validated();

                if ($request->hasFile('image')) {
                    if ($portfolio->image && Storage::disk('public')->exists($portfolio->image)) {
                        Storage::disk('public')->delete($portfolio->image);
                    }
                    $validated['image'] = $request->file('image')->store('portfolios', 'public');
                }

                if ($request->hasFile('image_portofolio')) {
                    if ($portfolio->image_portofolio && Storage::disk('public')->exists($portfolio->image_portofolio)) {
                        Storage::disk('public')->delete($portfolio->image_portofolio);
                    }
                    $validated['image_portofolio'] = $request->file('image_portofolio')->store('portfolio_images', 'public');
                }

                $portfolio->update($validated);
                return $portfolio;
            });

            return $this->successResponse($updatedPortfolio, 'Portfolio updated successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update portfolio.', 500);
        }
    }

    public function destroy(Portofolio $portfolio): JsonResponse
    {
        try {
            DB::transaction(function () use ($portfolio) {
                if ($portfolio->image && Storage::disk('public')->exists($portfolio->image)) {
                    Storage::disk('public')->delete($portfolio->image);
                }
                if ($portfolio->image_portofolio && Storage::disk('public')->exists($portfolio->image_portofolio)) {
                    Storage::disk('public')->delete($portfolio->image_portofolio);
                }
                $portfolio->delete();
            });

            return $this->successResponse(null, 'Portfolio deleted successfully.', 204);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete portfolio.', 500);
        }
    }
}
