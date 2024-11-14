<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceEntryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::post('/services', [ServiceController::class, 'store']);
Route::put('/services/{service}', [ServiceController::class, 'update']);
Route::delete('/services/{service}', [ServiceController::class, 'destroy']);

Route::get('/services/{serviceId}/entries', [ServiceEntryController::class, 'index']); // Afficher tous les enregistrements d'un service
Route::post('/services/{serviceId}/entries', [ServiceEntryController::class, 'store']); // Créer un nouvel enregistrement
Route::get('/entries/{entryId}', [ServiceEntryController::class, 'show']); // Afficher un enregistrement spécifique
Route::put('/entries/{entryId}', [ServiceEntryController::class, 'update']); // Mettre à jour un enregistrement spécifique
Route::delete('/entries/{entryId}', [ServiceEntryController::class, 'destroy']); // Supprimer un enregistrement spécifique
