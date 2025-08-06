<?php

use App\Http\Controllers\BusinessLineController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MicrodataOptionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CareerOppurtinitiesController;
use App\Http\Controllers\PositionController;
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

Route::get('/clients', [ClientController::class, 'index']);
Route::get('/clients/{id}', [ClientController::class, 'show']);

Route::get('/microdata-options', [MicrodataOptionController::class, 'index']);
Route::get('/microdata-options/{id}', [MicrodataOptionController::class, 'show']);

Route::get('/positions', [PositionController::class, 'index']);
Route::get('/positions', [PositionController::class, 'show']);


Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/user', [UserController::class, 'user']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::apiResource('teams', TeamController::class);
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('technologies', TechnologyController::class);
    Route::apiResource('careers', CareerController::class);
    Route::apiResource('about-us', AboutUsController::class);
    Route::apiResource('career-opportunities', CareerOppurtinitiesController::class);
    Route::apiResource('business-lines', BusinessLineController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('microdata-options', MicrodataOptionController::class);
    Route::apiResource('positions', PositionController::class);

});
