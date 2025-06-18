<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\ClienteController;

Route::post('/clientes', [ClienteController::class, 'store']);
Route::post('/clientes/{cliente}/aprovar', [ClienteController::class, 'aprovar']);
Route::get('/clientes', [ClienteController::class, 'index']);
