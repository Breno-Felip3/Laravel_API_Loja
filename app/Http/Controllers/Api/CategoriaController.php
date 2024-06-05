<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalvarAtualizarCategoriaRequest;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    protected $categoriaModel, $totalItensPagina = 10;
    public function __construct(Categoria $categoria)
    {
        $this->categoriaModel = $categoria;
    }

    public function index(Request $request)
    {
        $categoria = $this->categoriaModel->getCategorias($request->all());

        return response()->json($categoria);
    }

    public function salvar(SalvarAtualizarCategoriaRequest $request)
    {
        $categoria = $this->categoriaModel->create($request->validated());

        return response()->json($categoria);
    }

    public function atualizar(SalvarAtualizarCategoriaRequest $request, $idCategoria)
    {
        if(!$categoria = $this->categoriaModel->find($idCategoria)){
            return response()->json(['error' => 'Not found'], 404);
        }

        $categoria->update($request->validated());

        return response()->json($categoria);
    }

    public function deletar($idCategoria)
    {
        if (!$categoria = $this->categoriaModel->find($idCategoria)) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $categoria->delete();

        return response()->json(['message' => 'Categoria deletada com sucesso'], 204);
    }

    public function produtos($idCategoria)
    {
        if(!$categoria = $this->categoriaModel->find($idCategoria)){
            return response()->json(['error' => 'Not found'], 404);
        }

        $produtos = $categoria->produtos()->paginate($this->totalItensPagina);

        return response()->json([
            'categoria' => $categoria,
            'produtos' => $produtos
        ]);
    }
}
