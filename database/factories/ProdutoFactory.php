<?php

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;


class ProdutoFactory extends Factory
{
    protected $model = Produto::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'categoria_id' => 1,
            'nome' => $this->faker->unique()->word,
            'descricao' => $this->faker->sentence(),
        ];
    }
}
