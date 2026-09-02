<?php

namespace Database\Seeders;

use App\Models\Motorista;
use Illuminate\Database\Seeder;

class MotoristaSeeder extends Seeder
{
    private const NOMES = [
        'Carlos Eduardo Silva',
        'Ana Paula Ribeiro',
        'João Pedro Almeida',
        'Mariana Costa Santos',
        'Roberto Oliveira Lima',
    ];

    public function run(): void
    {
        foreach (self::NOMES as $nome) {
            Motorista::query()->firstOrCreate(['nome' => $nome]);
        }
    }
}
