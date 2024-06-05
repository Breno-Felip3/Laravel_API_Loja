<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProdutoController;

Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/categorias/{id}/produtos', [CategoriaController::class, 'produtos']);
Route::any('/categorias/salvar', [CategoriaController::class, 'salvar']);
Route::any('/categorias/atualizar/{id}', [CategoriaController::class, 'atualizar']);
Route::any('/categorias/deletar/{id}', [CategoriaController::class, 'deletar']);

Route::get('/produtos', [ProdutoController::class, 'index']);
Route::post('/produtos/salvar', [ProdutoController::class, 'salvar']);
Route::any('/produtos/editar/{id}', [ProdutoController::class, 'editar']);
Route::any('/produtos/deletar/{id}', [ProdutoController::class, 'deletar']);
Route::any('/produtos/getProduto/{id}', [ProdutoController::class, 'getProduto']);