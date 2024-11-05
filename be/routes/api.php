<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientController;

Route::prefix('clients')->group(function () {
    Route::get('/', [ClientController::class, 'index']);      // Get all clients
    Route::get('/{id}', [ClientController::class, 'show']);   // Get a specific client by ID
    Route::post('/add', [ClientController::class, 'store']);     // Create a new client
    Route::put('/{id}', [ClientController::class, 'update']); // Update a client by ID
    Route::post('remove/{id}', [ClientController::class, 'destroy']); // Recycle a client by ID
});
