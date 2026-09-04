<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResumoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'dias_proximos' => $this['dias_proximos'],
            'coletas_hoje' => $this['coletas_hoje'],
            'coletas_proximos_dias' => $this['coletas_proximos_dias'],
            'coletas_futuras' => $this['coletas_futuras'],
            'coletas_semana' => $this['coletas_semana'],
            'total_motoristas' => $this['total_motoristas'],
            'motoristas_com_coletas' => $this['motoristas_com_coletas'],
            'proximas_coletas' => ColetaResource::collection($this['proximas_coletas']),
            'carga_motoristas' => CargaMotoristaResource::collection($this['carga_motoristas']),
        ];
    }
}
