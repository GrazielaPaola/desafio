<?php

namespace Database\Seeders;

use App\Models\Coleta;
use App\Models\Motorista;
use Illuminate\Database\Seeder;

class ColetaSeeder extends Seeder
{
    private const PLACAS = ['ABC1D23', 'XYZ9876', 'BRA2E19', 'QWE4567', 'RST7F89'];

    private const COLETAS_POR_MOTORISTA = 2;

    public function run(): void
    {
        if (Coleta::query()->exists()) {
            return;
        }

        Motorista::query()->orderBy('id')->get()->each(function (Motorista $motorista, int $indice) {
            $placa = self::PLACAS[$indice % count(self::PLACAS)];

            Coleta::factory()
                ->count(self::COLETAS_POR_MOTORISTA)
                ->for($motorista)
                ->sequence(fn ($sequencia) => ['data' => now()->addDays($indice + $sequencia->index + 1)->toDateString()])
                ->create(['placa_veiculo' => $placa]);
        });
    }
}
