<?php

use App\Http\Controllers\AreaController;
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

// Utilidad: contar en cuántas áreas está inscrito un CI
Route::get('/inscritos/areas-por-ci/{ci}', [InscritoController::class, 'areasPorCi']);
