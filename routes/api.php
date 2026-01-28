<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\ProjetoController;
use App\Http\Controllers\Api\LancamentoController;

Route::get('/teste', function () {
    return response()->json([
        'status' => 'ok',
        'mensagem' => 'API funcionando'
    ]);
});

// ✅ rota do dashboard (ANTES do apiResource projetos)
Route::get('projetos/{projeto}/dashboard', [ProjetoController::class, 'dashboard']);

Route::apiResource('clientes', ClienteController::class);
Route::apiResource('projetos', ProjetoController::class);
Route::apiResource('lancamentos', LancamentoController::class);
