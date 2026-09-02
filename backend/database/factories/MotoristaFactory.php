<?php

namespace Database\Factories;

use App\Models\Motorista;
use Illuminate\Database\Eloquent\Factories\Factory;

class MotoristaFactory extends Factory
{
    protected $model = Motorista::class;

    public function definition(): array
    {
        return [
            'nome' => fake('pt_BR')->name(),
        ];
    }
}
