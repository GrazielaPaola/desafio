<?php

namespace App\Http\Resources;

use App\Support\Placa;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MotoristaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'total_coletas' => $this->whenCounted('coletas'),
            'placa_veiculo' => $this->when(
                $this->possuiAtributo('placa_veiculo'),
                fn () => Placa::formatar($this->placa_veiculo) ?: null,
            ),
            'proxima_coleta' => $this->when(
                $this->possuiAtributo('proxima_coleta'),
                fn () => $this->proxima_coleta,
            ),
        ];
    }

    private function possuiAtributo(string $atributo): bool
    {
        return array_key_exists($atributo, $this->resource->getAttributes());
    }
}
