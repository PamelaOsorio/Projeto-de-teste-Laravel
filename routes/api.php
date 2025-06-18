<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/clientes', [ClienteController::class, 'store']);
    Route::post('/clientes/{cliente}/aprovar', [ClienteController::class, 'aprovar']);
    Route::get('/clientes', [ClienteController::class, 'index']);
    Route::post('/clientes/{id}/reprovar', [ClienteController::class, 'reprovar']);

});
