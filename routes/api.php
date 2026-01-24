<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ClienteController;

Route::get('/teste', function () {
    return response()->json([
        'status' => 'ok',
        'mensagem' => 'API funcionando'
    ]);
});

// rota de exemplo (users)
Route::get('/users', [UserController::class, 'index']);

// CRUD completo de clientes
Route::apiResource('clientes', ClienteController::class);
