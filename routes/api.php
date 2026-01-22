<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('/teste', function () {
    return response()->json([
        'status' => 'ok',
        'mensagem' => 'API funcionando'
    ]);
});

Route::get('/users', [UserController::class, 'index']);
