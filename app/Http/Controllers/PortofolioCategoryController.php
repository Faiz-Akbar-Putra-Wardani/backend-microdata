<?php

namespace App\Http\Controllers;

use App\Models\PortofolioCategory;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PortofolioCategoryController extends Controller
{
    Use ApiResponse;
    public function index()
    {
        
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PortofolioCategory $portofolioCategory)
    {
        //
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
    public function update(Request $request, PortofolioCategory $portofolioCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PortofolioCategory $portofolioCategory)
    {
        //
    }
}
