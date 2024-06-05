<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalvarAtualizarProdutoRequest;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdutoController extends Controller
{
    protected $produtoModel, $totalItensPagina = 10;
    public function __construct(Produto $produto)
    {
        $this->produtoModel = $produto;
    }
    
    public function index(Request $request)
    {
        $produtos = $this->produtoModel->getProdutos($request->all(), $this->totalItensPagina);

        return response()->json($produtos);
    }

    public function salvar(SalvarAtualizarProdutoRequest $request)
    {
        $dados = $request->validated();

        //Verifica se existe uma imagem e se ela é uma imagem válida
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {

            //Nome da imagem igual ao nome do produto sem caracteres especiais
            $nome = Str::kebab($request->nome);
            $extensaoImagem = $request->imagem->extension();
        
            $nomeArquivo = "{$nome}.{$extensaoImagem}";
        
            //Atualiza o nome da imagem
            $dados['imagem'] = $nomeArquivo;
        
            //Salva a imagem
            $uploadImagem = $request->imagem->storeAs('produtos', $nomeArquivo);
        
            if (!$uploadImagem) {
                return response()->json(['erros' => 'Não foi possível salvar a imagem'], 500);
            }
        }

        $produto = $this->produtoModel->create($dados);

        return response()->json($produto, 201);
    }

    public function editar($idProduto, SalvarAtualizarProdutoRequest $request)
    {
        if(!$produto = $this->produtoModel->find($idProduto)){
            return response()->json(['error' => 'Not Found'], 404);
        }

        $dados = $request->validated();

        //Verifica se existe uma imagem e se ela é uma imagem válida
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {

            //Verifica se o arquivo da imagem já existe para excluir e cadastrar novamente
            if($produto->imagem){
                if(Storage::exists("produtos/{$produto->imagem}")){
                    Storage::delete("produtos/{$produto->imagem}");
                }
            }

            //Nome da imagem igual ao nome do produto sem caracteres especiais
            $nome = Str::kebab($request->nome);
            $extensaoImagem = $request->imagem->extension();
        
            $nomeArquivo = "{$nome}.{$extensaoImagem}";
        
            //Atualiza o nome da imagem
            $dados['imagem'] = $nomeArquivo;
        
            //Salva a imagem
            $uploadImagem = $request->imagem->storeAs('produtos', $nomeArquivo);
        
            if (!$uploadImagem) {
                return response()->json(['erros' => 'Não foi possível salvar a imagem'], 500);
            }
        }

        $produto->update($dados);
        return response()->json($produto);
    }

    public function deletar($idProduto)
    {
        if(!$produto = $this->produtoModel->find($idProduto)){
            return response()->json(['error' => 'Not Found'], 404);
        }

        //Verifica se o arquivo da imagem já existe para excluir
        if($produto->imagem){
            if(Storage::exists("produtos/{$produto->imagem}")){
                Storage::delete("produtos/{$produto->imagem}");
            }
        }

        $produto->delete();
        return response()->json(['Produto deletado']);
    }

    public function getProduto($idProduto)
    {
        if(!$produto = $this->produtoModel->with('categoria')->find($idProduto)){
            return response()->json(['error' => 'Not Found'], 404);
        }
       
        return $produto;
    }
}
