<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCareerOpportunitiesRequest;
use App\Http\Requests\UpdatedCareerOpportunitiesRequest;
use App\Models\CareerOppurtinities;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CareerOppurtinitiesController extends Controller
{
    Use ApiResponse;
    public function index()
    {
        try {
            $careerOpportunities = CareerOppurtinities::orderByDesc('created_at', 'desc')->get();
            $careerOpportunities->transform(function ($opportunity) {
                $opportunity->image_url = $opportunity->image ? asset('storage/' . $opportunity->image) : null;

                $opportunity->requirements = preg_split("/\r\n|\n|\r/", $opportunity->requirements);

                return $opportunity;
            });

            return $this->successResponse($careerOpportunities);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve career opportunities.', 500);
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
    public function store(StoreCareerOpportunitiesRequest $request): JsonResponse
    {
        try {
            $newCareerOpportunity = DB::transaction(function () use ($request) {
                $validated = $request->validated();

                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('images', 'public');
                    $validated['image'] = $imagePath;
                }
                return CareerOppurtinities::create($validated);
            });

            return $this->successResponse($newCareerOpportunity, 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create career opportunity.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $careerOpportunity = CareerOppurtinities::findOrFail($id);
            $careerOpportunity->image_url = $careerOpportunity->image ? asset('storage/' . $careerOpportunity->image) : null;

            $careerOpportunity->requirements = preg_split("/\r\n|\n|\r/", $careerOpportunity->requirements);

            return $this->successResponse($careerOpportunity);
        } catch (\Exception $e) {
            return $this->errorResponse('Career opportunity not found.', 404);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CareerOppurtinities $careerOppurtinities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedCareerOpportunitiesRequest $request, $id): JsonResponse
{
    try {
        $careerOpportunity = CareerOppurtinities::findOrFail($id);

        $updatedCareerOpportunity = DB::transaction(function () use ($request, $careerOpportunity) {
            $validated = $request->validated();

            if ($request->hasFile('image')) {
                if ($careerOpportunity->image && Storage::disk('public')->exists($careerOpportunity->image)) {
                    Storage::disk('public')->delete($careerOpportunity->image);
                }
                $imagePath = $request->file('image')->store('images', 'public');
                $validated['image'] = $imagePath;
            } else {
                unset($validated['image']);
            }

            $careerOpportunity->update($validated);
            return $careerOpportunity;
        });

        return $this->successResponse($updatedCareerOpportunity);
    } catch (\Exception $e) {
        return $this->errorResponse('Failed to update career opportunity.', 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
public function destroy($id): JsonResponse
{
    try {
        $careerOpportunity = CareerOppurtinities::findOrFail($id);

        DB::transaction(function () use ($careerOpportunity) {
            if ($careerOpportunity->image && Storage::disk('public')->exists($careerOpportunity->image)) {
                Storage::disk('public')->delete($careerOpportunity->image);
            }
            $careerOpportunity->delete();
        });

        return $this->successResponse(null, 'Career opportunity deleted successfully.', 200);
    } catch (\Exception $e) {
        return $this->errorResponse('Failed to delete career opportunity.', 500);
    }
}


}
