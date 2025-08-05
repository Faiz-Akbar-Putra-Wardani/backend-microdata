<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdatedClientRequest;
use App\Models\Client;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    Use ApiResponse;
    public function index() : JsonResponse
    {
        try {
            $clients = Client::orderByDesc('created_at', 'desc')->get();
            return $this->successResponse($clients);
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
    public function store(StoreClientRequest $request) :JsonResponse
    {
        try {
            $newClient = DB::transaction(function () use  ($request) {
                $validated = $request ->validated();
                return Client::create($validated);
            });
            return $this->successResponse($newClient, 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id) : JsonResponse
    {
        try {
            $client = Client::findOrFail($id);
            return $this->successResponse($client);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedClientRequest $request, Client $client) : JsonResponse
    {
        try {
            $updatedClient = DB::transaction(function () use ($request, $client) {
                $validated = $request->validated();
                $client->update($validated);
                return $client;
            });
            return $this->successResponse($updatedClient);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client) : JsonResponse
    {
        try {
            DB::transaction(function () use ($client) {
                $client->delete();
            });
            return $this->successResponse($client, 'Client deleted successfully.', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete client.', 500);
        }
    }
}
