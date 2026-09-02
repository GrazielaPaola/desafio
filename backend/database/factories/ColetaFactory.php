<?php

namespace Database\Factories;

use App\Models\Coleta;
use App\Models\Motorista;
use Illuminate\Database\Eloquent\Factories\Factory;

class ColetaFactory extends Factory
{
    protected $model = Coleta::class;

    private const DIAS_MINIMOS = 1;

    private const DIAS_MAXIMOS = 30;

    public function definition(): array
    {
        return [
            'data' => now()->addDays(fake()->numberBetween(self::DIAS_MINIMOS, self::DIAS_MAXIMOS))->toDateString(),
            'fornecedor_nome' => fake('pt_BR')->company(),
            'fornecedor_cnpj' => fake('pt_BR')->cnpj(false),
            'cliente_nome' => fake('pt_BR')->company(),
            'cliente_cnpj' => fake('pt_BR')->cnpj(false),
            'motorista_id' => Motorista::factory(),
            'placa_veiculo' => strtoupper(fake()->bothify('???#?##')),
        ];
    }
}
