<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoria_id',
        'nome',
        'descricao',
        'imagem'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function getProdutos($data, $total)
    {
        if(!isset($data['filter']) && !isset($data['nome']) && !isset($data['descricao'])){
            return $this->paginate($total);
        }

        $produtos = $this->where(function($query) use ($data){
            if(isset($data['filter'])){
                $filtro = $data['filter'];
                $query->where('nome', $filtro);
                $query->orWhere('descricao', 'LIKE', "%{$filtro}%");
            }

            if (isset($data['nome'])){
                $query->where('nome', $data['nome']);
            }

            if (isset($data['descricao'])){
                $termo = $data['descricao'];
                $query->where('descricao', 'LIKE', "%{$termo}%");
            }
        })->paginate($total); //toSql() dd

        return $produtos;
    }
}
