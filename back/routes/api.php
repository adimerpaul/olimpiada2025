<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InscritoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

//AREAS
Route::get('/areas', [AreaController::class, 'index']);

// Inscripción
Route::post('/inscritos', [InscritoController::class, 'store']);
Route::get('/inscripciones/{id}', [InscritoController::class, 'publicShow']);

// Utilidad: contar en cuántas áreas está inscrito un CI
Route::get('/inscritos/areas-por-ci/{ci}', [InscritoController::class, 'areasPorCi']);


Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/inscritos/count', [InscritoController::class, 'count']);

    Route::get('/inscritos', [InscritoController::class, 'index']);
    Route::get('/inscritos/{id}', [InscritoController::class, 'show']);
    Route::post('/inscritos/{id}', [InscritoController::class, 'update']);
    Route::delete('/inscritos/{id}', [InscritoController::class, 'destroy']);

    Route::get('/areas/{id}',   [AreaController::class, 'show']);
    Route::post('/areas',       [AreaController::class, 'store']);
    Route::put('/areas/{id}',   [AreaController::class, 'update']);
    Route::delete('/areas/{id}',[AreaController::class, 'destroy']);
});
