<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContasController;
use App\Http\Controllers\TransacoesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;

Route::middleware(['JsonMiddleware'])->group(function () {

    Route::post("auth", [AuthController::class, 'login']);

    Route::post("usuarios", [UsuariosController::class, 'create']);

    Route::middleware(['apiJWT'])->group(function () {

        Route::get("usuarios", [UsuariosController::class, 'buscaUsuarios'])->name('usuarios');
        Route::put("usuarios", [UsuariosController::class, 'alteraUsuario']);
        Route::delete("usuarios", [UsuariosController::class, 'deleteUsuario']);

        Route::get("usuario", [UsuariosController::class, 'buscaUsuario'])->name('usuarios');

        Route::post("transacao/deposito", [TransacoesController::class, 'depositar']);
        Route::get("transacao/extrato", [TransacoesController::class, 'extrato']);
        Route::post("transacao/saque", [TransacoesController::class, 'sacar']);

        Route::post("conta", [ContasController::class, 'novaConta']);
        Route::get("conta", [ContasController::class, 'buscaContas']);
        Route::put("conta", [ContasController::class, 'atualizaConta']);
        Route::delete("conta", [ContasController::class, 'apagaConta']);

        /** Informações do usuário logado */
        Route::get('auth/me', [AuthController::class, 'me'])->name('login');
        /** Encerra o acesso */
        Route::get('auth/logout', [AuthController::class, 'logout']);
        /** Atualiza o token */
        Route::get('auth/refresh', [AuthController::class, 'refresh']);
    });
});
