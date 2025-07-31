<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdatedTeamRequest;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    use ApiResponse;
    public function index(): JsonResponse
    {
        try {
            $teams = Team::orderBy('created_at', 'desc')->get();

            return $this->successResponse($teams, 'Teams retrieved successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve teams.', 500);
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
    public function store(StoreTeamRequest $request): JsonResponse
    {
        try {
        $newTeam = DB::transaction(function () use ($request) {
            $validated = $request->validated();

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
                $validated['photo'] = $photoPath;
            }
            return Team::create($validated);
        });

        return $this->successResponse($newTeam, 'Team created successfully.', 201);

    } catch (\Exception $e) {
        return $this->errorResponse( 'Failed to create team.', 500);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show($id) : JsonResponse
    {
        try {
            $team = Team::findOrFail($id);
            return $this->successResponse($team, 'Team retrieved successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve team.', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedTeamRequest $request, Team $team): JsonResponse
    {
        try {
            $updateTeam = DB::transaction(function () use ($request, $team) {
                $validated = $request->validated();

                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store('photos', 'public');
                    $validated['photo'] = $photoPath;
                } else {
                    unset($validated['photo']);
                }

                $team->update($validated);
                return $team->fresh();
            });

            return $this->successResponse($updateTeam, 'Team updated successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update team.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team): JsonResponse
    {
        try {
            DB::transaction(function () use ($team){
                if ($team->photo && Storage::disk('public')->exists($team->photo)){
                    Storage::disk('public')->delete($team->photo);
                }
                $team->delete();
            });
            return $this->successResponse($team, 'Team deleted successfully.', 200);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete team.', 500);
        }
    }
}
