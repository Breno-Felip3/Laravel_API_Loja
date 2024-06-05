<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome'
    ];

    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    public function getCategorias($nome)
    {
        if($nome == null){
            return $this->get();
        }
        
        return $this->where('nome', 'LIKE', "%{$nome}%")->get();
    }
}
