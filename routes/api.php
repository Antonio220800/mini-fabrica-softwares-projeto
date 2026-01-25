<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjetoController;
use App\Http\Controllers\Api\ClienteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/teste', function () {
    return response()->json([
        'status' => 'ok',
        'mensagem' => 'API funcionando'
    ]);
});

Route::apiResource('clientes', ClienteController::class);
Route::apiResource('projetos', ProjetoController::class);
