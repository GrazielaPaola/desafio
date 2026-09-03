<?php

namespace App\Services;

use App\Models\Coleta;
use App\Models\Motorista;

class ResumoService
{
    public const DIAS_PROXIMOS = 7;

    private const LIMITE_PROXIMAS_COLETAS = 5;

    public function gerar(): array
    {
        $hoje = now()->toDateString();
        $limiteProximosDias = now()->addDays(self::DIAS_PROXIMOS)->toDateString();

        return [
            'dias_proximos' => self::DIAS_PROXIMOS,
            'coletas_hoje' => Coleta::query()->whereDate('data', $hoje)->count(),
            'coletas_proximos_dias' => Coleta::query()->whereDate('data', '>', $hoje)->ate($limiteProximosDias)->count(),
            'coletas_futuras' => Coleta::query()->aPartirDe($hoje)->count(),
            'total_motoristas' => Motorista::query()->count(),
            'motoristas_com_coletas' => Motorista::query()->has('coletas')->count(),
            'proximas_coletas' => Coleta::query()
                ->with('motorista')
                ->aPartirDe($hoje)
                ->ordenadoPor('data', 'asc')
                ->limit(self::LIMITE_PROXIMAS_COLETAS)
                ->get(),
        ];
    }
}
