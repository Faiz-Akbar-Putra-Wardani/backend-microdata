<?php

use App\Http\Controllers\BusinessLineController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CareerOppurtinitiesController;
use App\Http\Controllers\UserController;

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

Route::get('/about-us', [AboutUsController::class, 'index']);
Route::get('/about-us/{id}', [AboutUsController::class, 'show']);

Route::get('/career-opportunities', [CareerOppurtinitiesController::class, 'index']);
Route::get('/career-opportunities/{id}', [CareerOppurtinitiesController::class, 'show']);

Route::get('/business-lines', [BusinessLineController::class, 'index']);
Route::get('/business-lines/{id}', [BusinessLineController::class, 'show']);

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::apiResource('teams', TeamController::class)->except(['index', 'show']);
    Route::apiResource('services', ServiceController::class)->except(['index', 'show']);
    Route::apiResource('technologies', TechnologyController::class)->except(['index', 'show']);
    Route::apiResource('careers', CareerController::class)->except(['index', 'show']);
    Route::apiResource('about-us', AboutUsController::class)->except(['index', 'show']);
    Route::apiResource('career-opportunities', CareerOppurtinitiesController::class)->except(['index', 'show']);
    Route::apiResource('business-lines', BusinessLineController::class)->except(['index', 'show']);
});
