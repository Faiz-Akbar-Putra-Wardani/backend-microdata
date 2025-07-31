<?php

use App\Http\Controllers\CareerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TechnologyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::get('/teams', [TeamController::class, 'index']);
Route::get('/teams/{id}', [TeamController::class, 'show']);
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::get('/technologies', [TechnologyController::class, 'index']);
Route::get('/technologies/{id}', [TechnologyController::class, 'show']);
Route::get('/careers', [CareerController::class, 'index']);
Route::get('/careers/{id}', [CareerController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('admin')->group(function () {
      
        Route::apiResource('teams', TeamController::class)->except(['index', 'show']);
        Route::apiResource('services', ServiceController::class)->except(['index', 'show']);
        Route::apiResource('technologies', TechnologyController::class)->except(['index', 'show']);
        Route::apiResource('careers', CareerController::class)->except(['index', 'show']);
    });

    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
