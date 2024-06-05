<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Auth\LoginApiController;

Route::post('login', [LoginApiController::class, 'login']);

Route::middleware('auth:api')->group(function(){
   
    Route::get('me', [LoginApiController::class, 'getUsuarioAutenticado']);
    Route::post('refresh', [LoginApiController::class, 'refreshToken']);
    Route::post('logout', [LoginApiController::class, 'logout']);

    Route::prefix('categorias')->group(function(){
        Route::get('/', [CategoriaController::class, 'index']);
        Route::get('/{id}/produtos', [CategoriaController::class, 'produtos']);
        Route::any('/salvar', [CategoriaController::class, 'salvar']);
        Route::any('/atualizar/{id}', [CategoriaController::class, 'atualizar']);
        Route::any('/deletar/{id}', [CategoriaController::class, 'deletar']);
    });

    Route::prefix('produtos')->group(function(){
        Route::get('/', [ProdutoController::class, 'index']);
        Route::post('/salvar', [ProdutoController::class, 'salvar']);
        Route::any('/editar/{id}', [ProdutoController::class, 'editar']);
        Route::any('/deletar/{id}', [ProdutoController::class, 'deletar']);
        Route::any('/getProduto/{id}', [ProdutoController::class, 'getProduto']);
    });
});





